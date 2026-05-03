<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Services\StandingsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::latest()->take(5)->get();
        $activeTournament = Tournament::where('status', 'active')->latest()->first();

        return view('admin.dashboard', compact('tournaments', 'activeTournament'));
    }

    public function chartData(Tournament $tournament)
    {
        $goalsByDay = $tournament->matches()
            ->where('status', 'finished')
            ->selectRaw('DATE(played_at) as day, SUM(home_score + away_score) as total_goals')
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn($r) => ['label' => $r->day, 'value' => $r->total_goals]);

        $finished = $tournament->matches()->where('status', 'finished')->get();
        $homeWins = $finished->filter(fn($m) => $m->home_score > $m->away_score)->count();
        $awayWins = $finished->filter(fn($m) => $m->home_score < $m->away_score)->count();
        $draws    = $finished->filter(fn($m) => $m->home_score === $m->away_score)->count();

        $teamsByGroup = $tournament->groups()->withCount('teams')->get()
            ->map(fn($g) => ['label' => 'Grupo ' . $g->name, 'value' => $g->teams_count]);

        return response()->json([
            'goals_by_day'   => $goalsByDay,
            'results'        => ['home' => $homeWins, 'away' => $awayWins, 'draw' => $draws],
            'teams_by_group' => $teamsByGroup,
        ]);
    }
}