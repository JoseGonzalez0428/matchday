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
            $teams = $group->teams->values();
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
}