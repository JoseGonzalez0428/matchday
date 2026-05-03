<?php

namespace App\Services;

use App\Models\Tournament;

class StandingsService
{
    public function calculate(Tournament $tournament): array
    {
        $standings = [];

        $groups = $tournament->groups()->with(['teams', 'matches' => function ($q) {
            $q->where('status', 'finished');
        }])->get();

        foreach ($groups as $group) {
            $groupStandings = [];

            foreach ($group->teams as $team) {
                $groupStandings[$team->id] = [
                    'team'   => $team,
                    'played' => 0,
                    'won'    => 0,
                    'drawn'  => 0,
                    'lost'   => 0,
                    'gf'     => 0,
                    'gc'     => 0,
                    'gd'     => 0,
                    'points' => 0,
                ];
            }

            foreach ($group->matches as $match) {
                $hId = $match->home_team_id;
                $aId = $match->away_team_id;
                $hG  = $match->home_score;
                $aG  = $match->away_score;

                if (!isset($groupStandings[$hId]) || !isset($groupStandings[$aId])) {
                    continue;
                }

                $groupStandings[$hId]['gf'] += $hG;
                $groupStandings[$hId]['gc'] += $aG;
                $groupStandings[$aId]['gf'] += $aG;
                $groupStandings[$aId]['gc'] += $hG;

                $groupStandings[$hId]['played']++;
                $groupStandings[$aId]['played']++;

                if ($hG > $aG) {
                    $groupStandings[$hId]['won']++;
                    $groupStandings[$aId]['lost']++;
                    $groupStandings[$hId]['points'] += 3;
                } elseif ($hG < $aG) {
                    $groupStandings[$aId]['won']++;
                    $groupStandings[$hId]['lost']++;
                    $groupStandings[$aId]['points'] += 3;
                } else {
                    $groupStandings[$hId]['drawn']++;
                    $groupStandings[$aId]['drawn']++;
                    $groupStandings[$hId]['points']++;
                    $groupStandings[$aId]['points']++;
                }
            }

            foreach ($groupStandings as &$row) {
                $row['gd'] = $row['gf'] - $row['gc'];
            }

            usort($groupStandings, function ($a, $b) {
                if ($a['points'] !== $b['points']) return $b['points'] - $a['points'];
                if ($a['gd']     !== $b['gd'])     return $b['gd']     - $a['gd'];
                if ($a['gf']     !== $b['gf'])     return $b['gf']     - $a['gf'];
                return strcmp($a['team']->name, $b['team']->name);
            });

            $standings[$group->name] = $groupStandings;
        }

        return $standings;
    }
}