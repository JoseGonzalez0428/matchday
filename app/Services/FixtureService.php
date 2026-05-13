<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Carbon\Carbon;

class FixtureService
{
    public function generateGroupStage(Tournament $tournament): int
    {
        $groups = $tournament->groups()->with('teams')->get();

        // Validar que existan grupos
        if ($groups->isEmpty()) {
            throw new \Exception("El torneo no tiene grupos creados. Crea al menos un grupo antes de generar el fixture.");
        }

        // Validar que cada grupo tenga al menos 3 equipos
        foreach ($groups as $group) {
            if ($group->teams->count() < 3) {
                throw new \Exception("El grupo {$group->name} necesita al menos 3 equipos para generar el fixture.");
            }
        }

        // Validar que todos los grupos tengan el mismo número de equipos
        $teamCounts = $groups->map(fn($g) => $g->teams->count())->unique();
        if ($teamCounts->count() > 1) {
            throw new \Exception("Todos los grupos deben tener el mismo número de equipos. Revisa la distribución.");
        }

        $matchesCreated = 0;
        $startDate = \Carbon\Carbon::parse($tournament->starts_at);
        $dayOffset = 0;


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

        $totalClassified = count($groupNames) * 2;

        if (!$hasQuarter && !$hasSemi) {
            // Con 8+ clasificados van a cuartos, con 4 van a semis, con 2 van a final
            if ($totalClassified >= 8) {
                $stage = 'quarter';
            } elseif ($totalClassified === 4) {
                $stage = 'semi';
            } else {
                $stage = 'final';
            }
        } elseif ($hasQuarter && !$hasSemi) {
            $pendingQuarters = $tournament->matches()
                ->where('stage', 'quarter')
                ->where('status', '!=', 'finished')
                ->count();
            if ($pendingQuarters > 0) {
                throw new \Exception("Aún hay {$pendingQuarters} partido(s) de cuartos pendientes por jugar.");
            }
            $stage = 'semi';
        } elseif ($hasSemi && $pendingSemis > 0) {
            throw new \Exception("Aún hay {$pendingSemis} semifinal(es) pendiente(s) por jugar.");
        } elseif ($hasSemi && $finishedSemis >= 2 && !$tournament->matches()->where('stage', 'final')->exists()) {
            $stage = 'final';
        } else {
            throw new \Exception("El torneo ya está en fase final.");
        }

        // Generar cruces 1°A vs 2°B, 1°B vs 2°A
        $fixtures = [];
        $startDate = \Carbon\Carbon::now()->addDays(3);

        if ($stage === 'semi' || $stage === 'quarter') {
            // Determinar cuántos clasifican por grupo (siempre los 2 primeros)
            $totalClassified = count($groupNames) * 2;

            // Validar que haya suficientes equipos clasificados
            foreach ($groupNames as $groupName) {
                if (!isset($standingsData[$groupName][0]) || !isset($standingsData[$groupName][1])) {
                    throw new \Exception("El grupo {$groupName} no tiene suficientes equipos clasificados.");
                }
            }

            // Generar cruces: 1°A vs 2°B, 1°B vs 2°A, 1°C vs 2°D, 1°D vs 2°C...
            $half = count($groupNames) / 2;
            for ($i = 0; $i < $half; $i++) {
                $groupA = $groupNames[$i];
                $groupB = $groupNames[$i + $half];

                // Cruce 1: 1° del primer grupo vs 2° del segundo grupo
                $fixtures[] = [
                    'home' => $standingsData[$groupA][0]['team'],
                    'away' => $standingsData[$groupB][1]['team'],
                ];

                // Cruce 2: 1° del segundo grupo vs 2° del primer grupo
                $fixtures[] = [
                    'home' => $standingsData[$groupB][0]['team'],
                    'away' => $standingsData[$groupA][1]['team'],
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