<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function show()
    {
        $team = auth()->user()->team;

        if (!$team) {
            return redirect()->route('captain.dashboard')
                ->with('error', 'No tienes un equipo asignado.');
        }

        $team->load('players');
        return view('captain.team', compact('team'));
    }

    public function playerShow(\App\Models\Player $player)
    {
        $user = auth()->user();
        $team = $user->team;

        // Verificar que el jugador pertenece al equipo del capitán
        if ($player->team_id !== $team->id) {
            abort(403, 'No tienes acceso a este jugador.');
        }

        $player->load([
            'goals.match.tournament',
            'goals.match.homeTeam',
            'goals.match.awayTeam',
            'team',
        ]);

        $totalGoals   = $player->goals->count();
        $regularGoals = $player->goals->where('type', 'regular')->count();
        $penaltyGoals = $player->goals->where('type', 'penalty')->count();
        $ownGoals     = $player->goals->where('type', 'own_goal')->count();

        $matchesPlayed = \App\Models\TournamentMatch::where('status', 'finished')
            ->where(fn($q) => $q->where('home_team_id', $team->id)
                ->orWhere('away_team_id', $team->id))
            ->count();

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

        $goalsByTournament = $player->goals
            ->groupBy(fn($g) => $g->match->tournament->name)
            ->map(fn($goals) => $goals->count());

        return view('captain.player-show', compact(
            'team', 'player',
            'totalGoals', 'regularGoals', 'penaltyGoals', 'ownGoals',
            'matchesPlayed', 'tournamentsWon', 'goalsByTournament'
        ));
    }
}