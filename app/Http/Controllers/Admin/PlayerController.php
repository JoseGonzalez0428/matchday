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
        return redirect()->route('admin.teams.players.index', $team);
    }
}