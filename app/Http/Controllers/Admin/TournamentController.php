<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tournament;
use App\Services\FixtureService;
use App\Services\StandingsService;
use Illuminate\Http\Request;

class TournamentController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::latest()->paginate(15);
        return view('admin.tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('admin.tournaments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:150',
            'edition'   => 'required|integer|min:2000|max:2100',
            'format'    => 'required|in:groups_knockout,league,knockout',
            'starts_at' => 'required|date',
            'ends_at'   => 'nullable|date|after:starts_at',
        ]);

        $validated['created_by'] = auth()->id();

        $tournament = Tournament::create($validated);

        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', 'Torneo creado exitosamente.');
    }

    public function show(Tournament $tournament)
    {
        $tournament->load('groups.teams');
        $standings = app(StandingsService::class)->calculate($tournament);
        return view('admin.tournaments.show', compact('tournament', 'standings'));
    }

    public function edit(Tournament $tournament)
    {
        return view('admin.tournaments.edit', compact('tournament'));
    }

    public function update(Request $request, Tournament $tournament)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:150',
            'edition'   => 'required|integer|min:2000|max:2100',
            'format'    => 'required|in:groups_knockout,league,knockout',
            'starts_at' => 'required|date',
            'ends_at'   => 'nullable|date|after:starts_at',
            'status'    => 'required|in:draft,active,finished',
        ]);

        $tournament->update($validated);

        return redirect()->route('admin.tournaments.show', $tournament)
            ->with('success', 'Torneo actualizado exitosamente.');
    }

    public function destroy(Tournament $tournament)
    {
        $tournament->delete();
        return redirect()->route('admin.tournaments.index')
            ->with('success', 'Torneo eliminado exitosamente.');
    }

    public function generateFixture(Tournament $tournament)
    {
        if ($tournament->matches()->exists()) {
            return back()->with('error', 'El fixture ya fue generado.');
        }

        try {
            $count = app(FixtureService::class)->generateGroupStage($tournament);
            $tournament->update(['status' => 'active']);
            return back()->with('success', "Fixture generado: {$count} partidos.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function generateNextRound(Tournament $tournament)
    {
        try {
            $count = app(FixtureService::class)->generateNextRound(
                $tournament,
                app(StandingsService::class)
            );

            $stage = $tournament->matches()->latest()->first()->stage;
            $stageNames = [
                'quarter' => 'Cuartos de final',
                'semi'    => 'Semifinales',
                'final'   => 'Final',
            ];

            return back()->with('success', 
                ($stageNames[$stage] ?? $stage) . " generadas: {$count} partidos."
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}