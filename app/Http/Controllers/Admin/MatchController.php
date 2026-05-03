<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TournamentMatch;
use App\Models\Tournament;
use App\Models\Goal;
use Illuminate\Http\Request;

class MatchController extends Controller
{
    public function index()
    {
        $matches = TournamentMatch::with(['tournament', 'homeTeam', 'awayTeam'])
            ->latest('played_at')
            ->paginate(15);
        return view('admin.matches.index', compact('matches'));
    }

    public function show(TournamentMatch $match)
    {
        $match->load(['homeTeam.players', 'awayTeam.players', 'goals.player', 'group']);
        return view('admin.matches.show', compact('match'));
    }

    public function edit(TournamentMatch $match)
    {
        $match->load(['homeTeam.players', 'awayTeam.players', 'goals']);
        return view('admin.matches.edit', compact('match'));
    }

    public function update(Request $request, TournamentMatch $match)
    {
        $validated = $request->validate([
            'home_score' => 'required|integer|min:0',
            'away_score' => 'required|integer|min:0',
            'goals'      => 'nullable|array',
            'goals.*.player_id' => 'nullable|exists:players,id',
            'goals.*.minute'    => 'required|integer|min:1|max:120',
            'goals.*.type'      => 'required|in:regular,penalty,own_goal',
        ]);

        $match->update([
            'home_score' => $validated['home_score'],
            'away_score' => $validated['away_score'],
            'status'     => 'finished',
        ]);

        // Reemplazar goles
        $match->goals()->delete();
        if (!empty($validated['goals'])) {
            foreach ($validated['goals'] as $goal) {
                Goal::create([
                    'match_id'  => $match->id,
                    'player_id' => $goal['player_id'] ?? null,
                    'minute'    => $goal['minute'],
                    'type'      => $goal['type'],
                ]);
            }
        }

        return redirect()->route('admin.matches.show', $match)
            ->with('success', 'Resultado registrado exitosamente.');
    }

    public function destroy(TournamentMatch $match)
    {
        if ($match->status !== 'scheduled') {
            return back()->with('error', 'Solo se pueden eliminar partidos programados.');
        }
        $match->delete();
        return redirect()->route('admin.matches.index')
            ->with('success', 'Partido eliminado exitosamente.');
    }

    public function create() { return redirect()->route('admin.matches.index'); }
    public function store(Request $request) { return redirect()->route('admin.matches.index'); }
}