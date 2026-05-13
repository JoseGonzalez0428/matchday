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

        if ($tournament->status === 'finished') {
            return back()->with('error', 'No puedes generar fixture en un torneo finalizado.');
        }

        // Validar que existan grupos
        $groups = $tournament->groups()->with('teams')->get();
        if ($groups->isEmpty()) {
            return back()->with('error', 'Debes crear al menos un grupo antes de generar el fixture.');
        }

        // Validar número par de grupos
        if ($groups->count() % 2 !== 0) {
            return back()->with('error', "El torneo tiene {$groups->count()} grupos. El número de grupos debe ser par (2, 4, 8...).");
        }

        // Validar mínimo 3 equipos por grupo
        foreach ($groups as $group) {
            if ($group->teams->count() < 3) {
                return back()->with('error', "El grupo {$group->name} tiene {$group->teams->count()} equipo(s). Necesita al menos 3 equipos para formar un grupo válido.");
            }
        }

        // Validar que todos los grupos tengan el mismo número de equipos
        $teamCounts = $groups->map(fn($g) => $g->teams->count())->unique();
        if ($teamCounts->count() > 1) {
            $details = $groups->map(fn($g) => "Grupo {$g->name}: {$g->teams->count()} equipos")->implode(', ');
            return back()->with('error', "Todos los grupos deben tener el mismo número de equipos. ({$details})");
        }

        try {
            $count = app(FixtureService::class)->generateGroupStage($tournament);
            $tournament->update(['status' => 'active']);
            return back()->with('success', "Fixture generado exitosamente: {$count} partidos.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function generateNextRound(Tournament $tournament)
    {
        if ($tournament->status === 'finished') {
            return back()->with('error', 'Este torneo ya está finalizado.');
        }

        // Validar que exista fixture de grupos
        if (!$tournament->matches()->where('stage', 'group')->exists()) {
            return back()->with('error', 'Primero debes generar el fixture de la fase de grupos.');
        }

        // Validar que no haya partidos de grupos pendientes
        $pendingGroups = $tournament->matches()
            ->where('stage', 'group')
            ->where('status', '!=', 'finished')
            ->count();

        if ($pendingGroups > 0) {
            return back()->with('error', "Aún hay {$pendingGroups} partido(s) de grupos pendientes por jugar.");
        }

        // Validar que no haya partidos eliminatorios pendientes
        $pendingKnockout = $tournament->matches()
            ->whereIn('stage', ['quarter', 'semi'])
            ->where('status', '!=', 'finished')
            ->count();

        if ($pendingKnockout > 0) {
            return back()->with('error', "Aún hay {$pendingKnockout} partido(s) eliminatorios pendientes por jugar.");
        }

        // Validar que no exista ya una final
        if ($tournament->matches()->where('stage', 'final')->exists()) {
            $finalPending = $tournament->matches()
                ->where('stage', 'final')
                ->where('status', '!=', 'finished')
                ->exists();

            if ($finalPending) {
                return back()->with('error', 'Ya existe una final programada. Juega ese partido primero.');
            }

            // Si la final ya se jugó, marcar torneo como finalizado
            $tournament->update(['status' => 'finished']);
            return back()->with('success', '¡El torneo ha finalizado!');
        }

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
                ($stageNames[$stage] ?? $stage) . " generadas exitosamente: {$count} partidos."
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function addGroup(Request $request, Tournament $tournament)
    {
        $request->validate([
            'name' => 'required|string|max:10|unique:groups,name,NULL,id,tournament_id,' . $tournament->id,
        ]);

        if ($tournament->matches()->exists()) {
            return back()->with('error', 'No puedes agregar grupos después de generar el fixture.');
        }

        if ($tournament->status === 'finished') {
            return back()->with('error', 'No puedes modificar un torneo finalizado.');
        }

        // Validar máximo 8 grupos
        if ($tournament->groups()->count() >= 8) {
            return back()->with('error', 'Un torneo no puede tener más de 8 grupos.');
        }

        $tournament->groups()->create(['name' => strtoupper($request->name)]);

        return back()->with('success', 'Grupo ' . strtoupper($request->name) . ' creado exitosamente.');
    }

    public function addTeamToGroup(Request $request, Tournament $tournament, \App\Models\Group $group)
    {
        $request->validate([
            'team_id' => 'required|exists:teams,id',
        ]);

        if ($tournament->matches()->exists()) {
            return back()->with('error', 'No puedes modificar grupos después de generar el fixture.');
        }

        if ($tournament->status === 'finished') {
            return back()->with('error', 'No puedes modificar un torneo finalizado.');
        }

        // Verificar que el equipo no esté ya en otro grupo del mismo torneo
        $alreadyInTournament = $tournament->groups()
            ->whereHas('teams', fn($q) => $q->where('teams.id', $request->team_id))
            ->exists();

        if ($alreadyInTournament) {
            return back()->with('error', 'Este equipo ya está en un grupo de este torneo.');
        }

        // Validar máximo de equipos por grupo (todos los grupos deben tener el mismo número)
        $maxTeamsPerGroup = $tournament->groups()
            ->withCount('teams')
            ->get()
            ->max('teams_count');

        if ($maxTeamsPerGroup && $group->teams()->count() >= $maxTeamsPerGroup && 
            $tournament->groups()->withCount('teams')->get()->min('teams_count') === $maxTeamsPerGroup) {
            return back()->with('error', "Todos los grupos ya tienen {$maxTeamsPerGroup} equipos. No puedes agregar más a este grupo.");
        }

        // Máximo 8 equipos por grupo
        if ($group->teams()->count() >= 8) {
            return back()->with('error', 'Un grupo no puede tener más de 8 equipos.');
        }

        $group->teams()->attach($request->team_id);

        return back()->with('success', 'Equipo agregado al grupo exitosamente.');
    }

    public function removeTeamFromGroup(Tournament $tournament, \App\Models\Group $group, \App\Models\Team $team)
    {
        if ($tournament->matches()->exists()) {
            return back()->with('error', 'No puedes modificar grupos después de generar el fixture.');
        }

        $group->teams()->detach($team->id);

        return back()->with('success', 'Equipo removido del grupo exitosamente.');
    }
}