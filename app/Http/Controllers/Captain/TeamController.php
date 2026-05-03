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
}