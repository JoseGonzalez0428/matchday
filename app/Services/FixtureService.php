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

        if (!$tournament->matches()->where('stage', 'group')->exists()) {
            throw new \Exception("No hay partidos de grupos registrados.");
        }

        $pendingGroups = $tournament->matches()
            ->where('stage', 'group')
            ->where('status', '!=', 'finished')
            ->count();

        if ($pendingGroups > 0) {
            throw new \Exception("Aún hay {$pendingGroups} partido(s) de grupos pendientes.");
        }

        $hasQuarter = $tournament->matches()->where('stage', 'quarter')->exists();
        $hasSemi    = $tournament->matches()->where('stage', 'semi')->exists();
        $hasFinal   = $tournament->matches()->where('stage', 'final')->exists();

        $pendingQuarters = $tournament->matches()->where('stage', 'quarter')->where('status', '!=', 'finished')->count();
        $pendingSemis    = $tournament->matches()->where('stage', 'semi')->where('status', '!=', 'finished')->count();
        $finishedSemis   = $tournament->matches()->where('stage', 'semi')->where('status', 'finished')->count();
        $totalSemis      = $tournament->matches()->where('stage', 'semi')->count();

        $totalClassified = count($groupNames) * 2;
        $fixtures  = [];
        $startDate = \Carbon\Carbon::now()->addDays(3);

        // ── Determinar etapa ──────────────────────────────────
        if (!$hasQuarter && !$hasSemi && !$hasFinal) {
            if ($totalClassified >= 8) {
                $stage = 'quarter';
            } elseif ($totalClassified === 4) {
                $stage = 'semi';
            } else {
                $stage = 'final';
            }
        } elseif ($hasQuarter && $pendingQuarters > 0) {
            throw new \Exception("Aún hay {$pendingQuarters} partido(s) de cuartos pendientes.");
        } elseif ($hasQuarter && !$hasSemi) {
            $stage = 'semi';
        } elseif ($hasSemi && $pendingSemis > 0) {
            throw new \Exception("Aún hay {$pendingSemis} semifinal(es) pendiente(s).");
        } elseif ($hasSemi && !$hasFinal) {
            $stage = 'final';
        } elseif ($hasFinal) {
            throw new \Exception("El torneo ya tiene una final programada.");
        } else {
            throw new \Exception("No se puede determinar la siguiente fase.");
        }

        // ── Generar cruces ────────────────────────────────────
        if ($stage === 'quarter' || $stage === 'semi') {

            if ($stage === 'quarter') {
                // Clasificados de grupos
                foreach ($groupNames as $groupName) {
                    if (!isset($standingsData[$groupName][0]) || !isset($standingsData[$groupName][1])) {
                        throw new \Exception("El grupo {$groupName} no tiene suficientes clasificados.");
                    }
                }

                $half = count($groupNames) / 2;
                for ($i = 0; $i < $half; $i++) {
                    $groupA = $groupNames[$i];
                    $groupB = $groupNames[$i + $half];

                    $fixtures[] = [
                        'home' => $standingsData[$groupA][0]['team'],
                        'away' => $standingsData[$groupB][1]['team'],
                    ];
                    $fixtures[] = [
                        'home' => $standingsData[$groupB][0]['team'],
                        'away' => $standingsData[$groupA][1]['team'],
                    ];
                }
            } else {
                // Semi: ganadores de cuartos
                $quarterMatches = $tournament->matches()
                    ->where('stage', 'quarter')
                    ->where('status', 'finished')
                    ->get();

                if ($quarterMatches->isEmpty()) {
                    // Viene directo de grupos (2 grupos de 3 o 4)
                    foreach ($groupNames as $groupName) {
                        if (!isset($standingsData[$groupName][0]) || !isset($standingsData[$groupName][1])) {
                            throw new \Exception("El grupo {$groupName} no tiene suficientes clasificados.");
                        }
                    }

                    $half = count($groupNames) / 2;
                    for ($i = 0; $i < $half; $i++) {
                        $groupA = $groupNames[$i];
                        $groupB = $groupNames[$i + $half];

                        $fixtures[] = [
                            'home' => $standingsData[$groupA][0]['team'],
                            'away' => $standingsData[$groupB][1]['team'],
                        ];
                        $fixtures[] = [
                            'home' => $standingsData[$groupB][0]['team'],
                            'away' => $standingsData[$groupA][1]['team'],
                        ];
                    }
                } else {
                    // Viene de cuartos — emparejar ganador C1 vs C2, C3 vs C4
                    $quarterMatches = $quarterMatches->sortBy('id')->values();
                    $winners = $quarterMatches->map(fn($m) =>
                        $m->home_score > $m->away_score ? $m->homeTeam : $m->awayTeam
                    )->values();

                    // Emparejar consecutivamente: G1 vs G2, G3 vs G4
                    for ($i = 0; $i < $winners->count(); $i += 2) {
                        if (isset($winners[$i]) && isset($winners[$i + 1])) {
                            $fixtures[] = [
                                'home' => $winners[$i],
                                'away' => $winners[$i + 1],
                            ];
                        }
                    }
                }
            }

        } elseif ($stage === 'final') {
            $semiMatches = $tournament->matches()
                ->where('stage', 'semi')
                ->where('status', 'finished')
                ->get();

            if ($semiMatches->count() < $totalSemis) {
                throw new \Exception("Aún hay semifinales pendientes por jugar.");
            }

            $winners = $semiMatches->map(fn($m) =>
                $m->home_score > $m->away_score ? $m->homeTeam : $m->awayTeam
            );

            $fixtures = [['home' => $winners[0], 'away' => $winners[1]]];
        }

        $this->generateKnockoutRound($tournament, $fixtures, $stage, $startDate);

        return count($fixtures);
    }
}