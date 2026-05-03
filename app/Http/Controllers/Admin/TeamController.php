<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('captain')->latest()->paginate(15);
        return view('admin.teams.index', compact('teams'));
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
        Team::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Equipo creado exitosamente.');
    }

    public function show(Team $team)
    {
        $team->load('players', 'captain');
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
        if ($team->shield_url) {
            Storage::disk('public')->delete($team->shield_url);
        }
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Equipo eliminado exitosamente.');
    }
}