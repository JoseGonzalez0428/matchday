<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Services\StandingsService;
use Illuminate\Http\JsonResponse;

class TournamentApiController extends Controller
{
    public function index(): JsonResponse
    {
        $tournaments = Tournament::where('status', 'active')
            ->select(['id', 'name', 'edition', 'format', 'status', 'starts_at', 'ends_at'])
            ->paginate(10);

        return response()->json($tournaments, 200);
    }

    public function show(Tournament $tournament): JsonResponse
    {
        return response()->json([
            'tournament' => $tournament->load('groups.teams'),
        ], 200);
    }

    public function standings(Tournament $tournament, StandingsService $service): JsonResponse
    {
        $standings = $service->calculate($tournament);

        $serialized = [];
        foreach ($standings as $groupName => $teams) {
            $serialized[$groupName] = array_map(fn($row) => [
                'team'   => ['id' => $row['team']->id, 'name' => $row['team']->name],
                'played' => $row['played'],
                'won'    => $row['won'],
                'drawn'  => $row['drawn'],
                'lost'   => $row['lost'],
                'gf'     => $row['gf'],
                'gc'     => $row['gc'],
                'gd'     => $row['gd'],
                'points' => $row['points'],
            ], $teams);
        }

        return response()->json(['standings' => $serialized], 200);
    }

    public function matches(Tournament $tournament): JsonResponse
    {
        $matches = $tournament->matches()
            ->with(['homeTeam:id,name', 'awayTeam:id,name', 'group:id,name'])
            ->orderBy('played_at')
            ->get();

        return response()->json(['matches' => $matches], 200);
    }
}