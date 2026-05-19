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
        $teamCounts = $groups->map(function($g) { return $g->teams->count(); })->unique();
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

        $hasRound32 = $tournament->matches()->where('stage', 'round32')->exists();
        $hasRound16 = $tournament->matches()->where('stage', 'round16')->exists();
        $hasQuarter = $tournament->matches()->where('stage', 'quarter')->exists();
        $hasSemi    = $tournament->matches()->where('stage', 'semi')->exists();
        $hasFinal   = $tournament->matches()->where('stage', 'final')->exists();

        $pendingRound32  = $tournament->matches()->where('stage', 'round32')->where('status', '!=', 'finished')->count();
        $pendingRound16  = $tournament->matches()->where('stage', 'round16')->where('status', '!=', 'finished')->count();
        $pendingQuarters = $tournament->matches()->where('stage', 'quarter')->where('status', '!=', 'finished')->count();
        $pendingSemis    = $tournament->matches()->where('stage', 'semi')->where('status', '!=', 'finished')->count();
        $totalSemis      = $tournament->matches()->where('stage', 'semi')->count();

        $totalClassified  = count($groupNames) * 2;
        $hasThirdPlace    = count($groupNames) >= 12;
        $totalWithThirds  = $hasThirdPlace ? $totalClassified + 8 : $totalClassified;

        $fixtures  = [];
        // Usar la fecha del último partido del torneo + 3 días
        $lastMatch = $tournament->matches()
            ->where('status', 'finished')
            ->orderByDesc('played_at')
            ->first();

        $startDate = $lastMatch 
            ? \Carbon\Carbon::parse($lastMatch->played_at)->addDays(3)
            : \Carbon\Carbon::now()->addDays(3);
        

        // ── Determinar etapa ──────────────────────────────────
        if (!$hasRound32 && !$hasRound16 && !$hasQuarter && !$hasSemi && !$hasFinal) {
            if ($totalWithThirds >= 32) {
                $stage = 'round32';
            } elseif ($totalClassified >= 16) {
                $stage = 'round16';
            } elseif ($totalClassified >= 8) {
                $stage = 'quarter';
            } elseif ($totalClassified === 4) {
                $stage = 'semi';
            } else {
                $stage = 'final';
            }
        } elseif ($hasRound32 && $pendingRound32 > 0) {
            throw new \Exception("Aún hay {$pendingRound32} partido(s) de octavos pendientes.");
        } elseif ($hasRound32 && !$hasRound16) {
            $stage = 'round16';
        } elseif ($hasRound16 && $pendingRound16 > 0) {
            throw new \Exception("Aún hay {$pendingRound16} partido(s) de dieciseisavos pendientes.");
        } elseif ($hasRound16 && !$hasQuarter) {
            $stage = 'quarter';
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
        if ($stage === 'round32') {
            $first  = [];
            $second = [];

            foreach ($groupNames as $g) {
                if (isset($standingsData[$g][0])) $first[$g]  = $standingsData[$g][0]['team'];
                if (isset($standingsData[$g][1])) $second[$g] = $standingsData[$g][1]['team'];
            }

            $best8 = $standings->bestThirdPlace($standingsData, 8);
            $thirdsByGroup = [];
            foreach ($best8 as $t3) {
                $thirdsByGroup[$t3['group']] = $t3['team'];
            }

            // Función para obtener el mejor tercero disponible de una lista de grupos
            $usedThirds = [];
            $getBestThird = function(array $possibleGroups) use ($thirdsByGroup, &$usedThirds) {
                // Ordenar por ranking (ya vienen ordenados en $thirdsByGroup)
                foreach ($possibleGroups as $g) {
                    if (isset($thirdsByGroup[$g]) && !in_array($g, $usedThirds)) {
                        $usedThirds[] = $g;
                        return $thirdsByGroup[$g];
                    }
                }
                return null;
            };

            $fixtures = [];

            // ── Cruces fijos (2° vs 2°) ───────────────────────────
            if (isset($second['A'], $second['B']))
                $fixtures[] = ['home' => $second['A'], 'away' => $second['B']]; // M73: 2A vs 2B

            if (isset($second['D'], $second['G']))
                $fixtures[] = ['home' => $second['D'], 'away' => $second['G']]; // M88: 2D vs 2G

            if (isset($second['E'], $second['I']))
                $fixtures[] = ['home' => $second['E'], 'away' => $second['I']]; // M78: 2E vs 2I

            if (isset($second['K'], $second['L']))
                $fixtures[] = ['home' => $second['K'], 'away' => $second['L']]; // M83: 2K vs 2L

            // ── Cruces fijos (1° vs 2°) ───────────────────────────
            if (isset($first['C'], $second['F']))
                $fixtures[] = ['home' => $first['C'], 'away' => $second['F']];  // M76: 1C vs 2F

            if (isset($first['F'], $second['C']))
                $fixtures[] = ['home' => $first['F'], 'away' => $second['C']];  // M75: 1F vs 2C

            if (isset($first['H'], $second['J']))
                $fixtures[] = ['home' => $first['H'], 'away' => $second['J']];  // M84: 1H vs 2J

            if (isset($first['J'], $second['H']))
                $fixtures[] = ['home' => $first['J'], 'away' => $second['H']];  // M86: 1J vs 2H

            // ── Cruces con mejores terceros (1° vs 3°) ────────────
            // Orden importante: los terceros más fuertes van primero
            $t74 = $getBestThird(['A','B','C','D','F']); // M74: 1E vs 3°(ABCDF)
            if (isset($first['E']) && $t74)
                $fixtures[] = ['home' => $first['E'], 'away' => $t74];

            $t77 = $getBestThird(['C','D','F','G','H']); // M77: 1I vs 3°(CDFGH)
            if (isset($first['I']) && $t77)
                $fixtures[] = ['home' => $first['I'], 'away' => $t77];

            $t79 = $getBestThird(['C','E','F','H','I']); // M79: 1A vs 3°(CEFHI)
            if (isset($first['A']) && $t79)
                $fixtures[] = ['home' => $first['A'], 'away' => $t79];

            $t80 = $getBestThird(['E','H','I','J','K']); // M80: 1L vs 3°(EHIJK)
            if (isset($first['L']) && $t80)
                $fixtures[] = ['home' => $first['L'], 'away' => $t80];

            $t81 = $getBestThird(['B','E','F','I','J']); // M81: 1D vs 3°(BEFIJ)
            if (isset($first['D']) && $t81)
                $fixtures[] = ['home' => $first['D'], 'away' => $t81];

            $t82 = $getBestThird(['A','E','H','I','J']); // M82: 1G vs 3°(AEHIJ)
            if (isset($first['G']) && $t82)
                $fixtures[] = ['home' => $first['G'], 'away' => $t82];

            $t85 = $getBestThird(['E','F','G','I','J']); // M85: 1B vs 3°(EFGIJ)
            if (isset($first['B']) && $t85)
                $fixtures[] = ['home' => $first['B'], 'away' => $t85];

            $t87 = $getBestThird(['D','E','I','J','L']); // M87: 1K vs 3°(DEIJL)
            if (isset($first['K']) && $t87)
                $fixtures[] = ['home' => $first['K'], 'away' => $t87];

            // ── Verificar que se generaron 16 cruces ──────────────
            // Si faltan cruces, completar con terceros no usados vs primeros sin par
            if (count($fixtures) < 16) {
                $pairedFirsts = array_map(fn($f) => $f['home']->id, $fixtures);
                $remainingFirsts = array_filter($first, 
                    fn($team) => !in_array($team->id, $pairedFirsts)
                );
                $remainingThirds = array_filter($thirdsByGroup,
                    fn($team) => !in_array($team->id, array_map(fn($f) => $f['away']->id, $fixtures))
                );

                $remainingFirstsList  = array_values($remainingFirsts);
                $remainingThirdsList  = array_values($remainingThirds);

                foreach ($remainingFirstsList as $i => $team) {
                    if (isset($remainingThirdsList[$i])) {
                        $fixtures[] = [
                            'home' => $team,
                            'away' => $remainingThirdsList[$i],
                        ];
                    }
                    if (count($fixtures) >= 16) break;
                }
            }

            if (count($fixtures) !== 16) {
                throw new \Exception(
                    "No se pudieron generar los 16 cruces. Se generaron " . count($fixtures) . "."
                );
            }


        } elseif (in_array($stage, ['round16', 'quarter', 'semi'])) {

            $prevStage = match($stage) {
                'round16' => 'round32',
                'quarter' => 'round16',
                'semi'    => 'quarter',
                default   => 'round32',
            };
            $prevMatches = $tournament->matches()
                ->where('stage', $prevStage)
                ->where('status', 'finished')
                ->orderBy('id')
                ->get();

            if ($prevMatches->isEmpty()) {
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
                $winners = $prevMatches->map(function($m) {
                    if (!is_null($m->home_penalties)) {
                        return $m->home_penalties > $m->away_penalties ? $m->homeTeam : $m->awayTeam;
                    }
                    return $m->home_score > $m->away_score ? $m->homeTeam : $m->awayTeam;
                })->values();

                for ($i = 0; $i < $winners->count(); $i += 2) {
                    if (isset($winners[$i]) && isset($winners[$i + 1])) {
                        $fixtures[] = [
                            'home' => $winners[$i],
                            'away' => $winners[$i + 1],
                        ];
                    }
                }
            }

        } elseif ($stage === 'final') {

            $semiMatches = $tournament->matches()
                ->where('stage', 'semi')
                ->where('status', 'finished')
                ->orderBy('id')
                ->get();

            if ($semiMatches->count() < $totalSemis) {
                throw new \Exception("Aún hay semifinales pendientes por jugar.");
            }

            $winners = $semiMatches->map(function($m) {
                if (!is_null($m->home_penalties)) {
                    return $m->home_penalties > $m->away_penalties ? $m->homeTeam : $m->awayTeam;
                }
                return $m->home_score > $m->away_score ? $m->homeTeam : $m->awayTeam;
            });

            $fixtures = [['home' => $winners[0], 'away' => $winners[1]]];
        }

        $this->generateKnockoutRound($tournament, $fixtures, $stage, $startDate);

        return count($fixtures);
    }
}