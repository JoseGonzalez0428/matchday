<?php

namespace App\Http\Controllers\Captain;

use App\Http\Controllers\Controller;
use App\Models\TournamentMatch;
use App\Services\MatchAnalysisService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $team = $user->team;

        $nextMatch     = null;
        $recentMatches = collect();
        $analysis      = null;

        if ($team) {
            $nextMatch = TournamentMatch::where('status', 'scheduled')
                ->where(fn($q) => $q->where('home_team_id', $team->id)
                    ->orWhere('away_team_id', $team->id))
                ->orderBy('played_at')
                ->with(['homeTeam', 'awayTeam'])
                ->first();

            $recentMatches = TournamentMatch::where('status', 'finished')
                ->where(fn($q) => $q->where('home_team_id', $team->id)
                    ->orWhere('away_team_id', $team->id))
                ->orderByDesc('played_at')
                ->with(['homeTeam', 'awayTeam'])
                ->take(5)
                ->get();

            if ($nextMatch) {
                //try {
                //    $analysis = app(MatchAnalysisService::class)->analyze($team, $nextMatch);
                //} catch (\Exception $e) {
                //    $analysis = null;
                //}
            }
        }

        return view('captain.dashboard', compact('team', 'nextMatch', 'recentMatches', 'analysis'));
    }
}