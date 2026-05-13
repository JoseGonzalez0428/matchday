<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Carbon\Carbon;

class FixtureService
{
    public function generateGroupStage(Tournament $tournament): int
    {
        $matchesCreated = 0;
        $startDate = Carbon::parse($tournament->starts_at);
        $dayOffset = 0;

        $groups = $tournament->groups()->with('teams')->get();

        foreach ($groups as $group) {
            $teams = $group->teams->shuffle()->values();
            $n = $teams->count();

            if ($n < 2) {
                throw new \Exception("Grupo {$group->name} tiene menos de 2 equipos.");
            }

            for ($i = 0; $i < $n - 1; $i++) {
                for ($j = $i + 1; $j < $n; $j++) {
                    TournamentMatch::create([
                        'tournament_id' => $tournament->id,
                        'group_id'      => $group->id,
                        'home_team_id'  => $teams[$i]->id,
                        'away_team_id'  => $teams[$j]->id,
                        'stage'         => 'group',
                        'status'        => 'scheduled',
                        'played_at'     => $startDate->copy()
                                            ->addDays($dayOffset)
                                            ->setHour($matchesCreated % 2 === 0 ? 16 : 19)
                                            ->setMinute(0),
                    ]);
                    $matchesCreated++;
                    if ($matchesCreated % 2 === 0) $dayOffset++;
                }
            }
        }

        return $matchesCreated;
    }

    public function generateKnockoutRound(
        Tournament $tournament,
        array $fixtures,
        string $stage,
        Carbon $startDate
    ): void {
        foreach ($fixtures as $i => $pair) {
            TournamentMatch::create([
                'tournament_id' => $tournament->id,
                'group_id'      => null,
                'home_team_id'  => $pair['home']->id,
                'away_team_id'  => $pair['away']->id,
                'stage'         => $stage,
                'status'        => 'scheduled',
                'played_at'     => $startDate->copy()->addDays($i),
            ]);
        }
    }

    public function generateNextRound(Tournament $tournament, StandingsService $standings): int
    {
        $standingsData = $standings->calculate($tournament);
        $groupNames = array_keys($standingsData);

        if (count($groupNames) < 2) {
            throw new \Exception("Se necesitan al menos 2 grupos para generar la siguiente fase.");
        }

        // Determinar la siguiente fase
        $hasGroupMatches = $tournament->matches()->where('stage', 'group')->exists();
        $hasSemi = $tournament->matches()->where('stage', 'semi')->exists();
        $hasQuarter = $tournament->matches()->where('stage', 'quarter')->exists();

        if (!$hasGroupMatches) {
            throw new \Exception("No hay partidos de grupos registrados.");
        }

        $pendingGroups = $tournament->matches()
            ->where('stage', 'group')
            ->where('status', '!=', 'finished')
            ->exists();

        if ($pendingGroups) {
            throw new \Exception("Aún hay partidos de grupos pendientes por jugar.");
        }

        $pendingSemis = $tournament->matches()
            ->where('stage', 'semi')
            ->where('status', '!=', 'finished')
            ->count();

        $finishedSemis = $tournament->matches()
            ->where('stage', 'semi')
            ->where('status', 'finished')
            ->count();

        if (!$hasQuarter && !$hasSemi) {
            $stage = count($groupNames) > 4 ? 'quarter' : 'semi';
        } elseif ($hasQuarter && !$hasSemi) {
            $stage = 'semi';
        } elseif ($hasSemi && $pendingSemis > 0) {
            throw new \Exception("Aún hay semifinales pendientes por jugar.");
        } elseif ($hasSemi && $finishedSemis >= 2 && !$tournament->matches()->where('stage', 'final')->exists()) {
            $stage = 'final';
        } else {
            throw new \Exception("El torneo ya está en fase final.");
        }

        // Generar cruces 1°A vs 2°B, 1°B vs 2°A
        $fixtures = [];
        $startDate = \Carbon\Carbon::now()->addDays(3);

        if ($stage === 'semi' || $stage === 'quarter') {
            for ($i = 0; $i < count($groupNames); $i++) {
                $groupA = $groupNames[$i];
                $groupB = $groupNames[($i + 1) % count($groupNames)];

                if (!isset($standingsData[$groupA][0]) || !isset($standingsData[$groupB][1])) {
                    throw new \Exception("No hay suficientes equipos clasificados.");
                }

                $fixtures[] = [
                    'home' => $standingsData[$groupA][0]['team'],
                    'away' => $standingsData[$groupB][1]['team'],
                ];
            }
        } elseif ($stage === 'final') {
            $semiWinners = $tournament->matches()
                ->where('stage', 'semi')
                ->where('status', 'finished')
                ->get();

            if ($semiWinners->count() < 2) {
                throw new \Exception("Aún hay semifinales pendientes.");
            }

            foreach ($semiWinners as $semi) {
                $winner = $semi->home_score > $semi->away_score
                    ? $semi->homeTeam
                    : $semi->awayTeam;
                $fixtures[] = $winner;
            }

            $fixtures = [['home' => $fixtures[0], 'away' => $fixtures[1]]];
        }

        $this->generateKnockoutRound($tournament, $fixtures, $stage, $startDate);

        return count($fixtures);
    }
}