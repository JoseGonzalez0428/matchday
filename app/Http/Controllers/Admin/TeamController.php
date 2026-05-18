<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\TeamRegisteredMail;
use Illuminate\Support\Facades\Mail;

class TeamController extends Controller
{
    public function index()
    {
        $search = request('search');
        $teams = Team::with('captain')
            ->withCount('players')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.teams.index', compact('teams', 'search'));
    }

    public function create()
    {
        $users = User::role('captain')->get();
        return view('admin.teams.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'captain_id' => 'nullable|exists:users,id',
            'country'    => 'nullable|string|max:60',
            'shield'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('shield')) {
            $validated['shield_url'] = $request->file('shield')->store('shields', 'public');
        }

        unset($validated['shield']);
        $team = Team::create($validated);

        // Enviar correo al capitán si tiene uno asignado
        if ($team->captain && $team->captain->email) {
            $tournament = \App\Models\Tournament::where('status', 'active')->latest()->first();
            Mail::to($team->captain->email)
                ->send(new TeamRegisteredMail($team, $tournament));
            }

        return redirect()->route('admin.teams.index')
            ->with('success', 'Equipo creado exitosamente.');
    }

    public function show(Team $team)
    {
        $team->load('players', 'captain', 'groups.tournament');
        return view('admin.teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $users = User::role('captain')->get();
        return view('admin.teams.edit', compact('team', 'users'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:100',
            'captain_id' => 'nullable|exists:users,id',
            'country'    => 'nullable|string|max:60',
            'shield'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('shield')) {
            if ($team->shield_url) {
                Storage::disk('public')->delete($team->shield_url);
            }
            $validated['shield_url'] = $request->file('shield')->store('shields', 'public');
        }

        unset($validated['shield']);
        $team->update($validated);

        return redirect()->route('admin.teams.show', $team)
            ->with('success', 'Equipo actualizado exitosamente.');
    }

    public function destroy(Team $team)
    {
        if ($team->homeMatches()->exists() || $team->awayMatches()->exists()) {
            return back()->with('error', 'No puedes eliminar este equipo porque tiene partidos registrados.');
        }

        $team->delete();
        return back()->with('success', 'Equipo eliminado exitosamente.');
    }
}