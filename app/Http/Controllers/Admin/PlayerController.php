<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Team $team)
    {
        $players = $team->players()->orderBy('dorsal')->get();
        return view('admin.players.index', compact('team', 'players'));
    }

    public function create(Team $team)
    {
        return view('admin.players.create', compact('team'));
    }

    public function store(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'dorsal'      => 'required|integer|min:1|max:99|unique:players,dorsal,NULL,id,team_id,' . $team->id,
            'position'    => 'required|in:GK,DEF,MID,FWD',
            'nationality' => 'nullable|string|max:60',
        ]);

        $validated['team_id'] = $team->id;
        Player::create($validated);

        return redirect()->route('admin.teams.players.index', $team)
            ->with('success', 'Jugador agregado exitosamente.');
    }

    public function edit(Team $team, Player $player)
    {
        return view('admin.players.edit', compact('team', 'player'));
    }

    public function update(Request $request, Team $team, Player $player)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'dorsal'      => 'required|integer|min:1|max:99|unique:players,dorsal,' . $player->id . ',id,team_id,' . $team->id,
            'position'    => 'required|in:GK,DEF,MID,FWD',
            'nationality' => 'nullable|string|max:60',
        ]);

        $player->update($validated);

        return redirect()->route('admin.teams.players.index', $team)
            ->with('success', 'Jugador actualizado exitosamente.');
    }

    public function destroy(Team $team, Player $player)
    {
        $player->delete();
        return redirect()->route('admin.teams.players.index', $team)
            ->with('success', 'Jugador eliminado exitosamente.');
    }

    public function show(Team $team, Player $player)
    {
        $player->load([
            'goals.match.tournament',
            'goals.match.homeTeam',
            'goals.match.awayTeam',
            'team',
        ]);

        // Estadísticas generales
        $totalGoals    = $player->goals->count();
        $regularGoals  = $player->goals->where('type', 'regular')->count();
        $penaltyGoals  = $player->goals->where('type', 'penalty')->count();
        $ownGoals      = $player->goals->where('type', 'own_goal')->count();

        // Partidos jugados (donde el equipo del jugador participó)
        $matchesPlayed = \App\Models\TournamentMatch::where('status', 'finished')
            ->where(fn($q) => $q->where('home_team_id', $team->id)
                ->orWhere('away_team_id', $team->id))
            ->count();

        // Torneos ganados
        $tournamentsWon = \App\Models\TournamentMatch::where('stage', 'final')
            ->where('status', 'finished')
            ->where(function($q) use ($team) {
                $q->where(function($q2) use ($team) {
                    $q2->where('home_team_id', $team->id)
                    ->whereColumn('home_score', '>', 'away_score');
                })->orWhere(function($q2) use ($team) {
                    $q2->where('home_team_id', $team->id)
                    ->whereColumn('home_penalties', '>', 'away_penalties');
                })->orWhere(function($q2) use ($team) {
                    $q2->where('away_team_id', $team->id)
                    ->whereColumn('away_score', '>', 'home_score');
                })->orWhere(function($q2) use ($team) {
                    $q2->where('away_team_id', $team->id)
                    ->whereColumn('away_penalties', '>', 'home_penalties');
                });
            })
            ->with('tournament')
            ->get()
            ->pluck('tournament')
            ->unique('id');

        // Goles por torneo
        $goalsByTournament = $player->goals
            ->groupBy(fn($g) => $g->match->tournament->name)
            ->map(fn($goals) => $goals->count());

        return view('admin.players.show', compact(
            'team', 'player',
            'totalGoals', 'regularGoals', 'penaltyGoals', 'ownGoals',
            'matchesPlayed', 'tournamentsWon', 'goalsByTournament'
        ));
    }
}