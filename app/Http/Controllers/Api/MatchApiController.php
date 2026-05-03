<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TournamentMatch;
use App\Models\Goal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MatchApiController extends Controller
{
    public function storeResult(Request $request, TournamentMatch $match): JsonResponse
    {
        if (!$request->user()->hasRole('admin')) {
            return response()->json(['message' => 'No autorizado.'], 403);
        }

        $validated = $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
        ]);

        $match->update([
            'home_score' => $validated['home_score'],
            'away_score' => $validated['away_score'],
            'status'     => 'finished',
        ]);

        return response()->json([
            'message' => 'Resultado registrado exitosamente.',
            'match'   => $match->fresh()->load(['homeTeam:id,name', 'awayTeam:id,name']),
        ], 200);
    }
}