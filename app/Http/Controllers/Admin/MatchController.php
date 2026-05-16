<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TournamentMatch;
use App\Models\Tournament;
use App\Models\Goal;
use Illuminate\Http\Request;
use App\Mail\MatchResultMail;
use Illuminate\Support\Facades\Mail;

class MatchController extends Controller
{
    public function index()
    {
        $matches = TournamentMatch::with(['tournament', 'homeTeam', 'awayTeam'])
            ->latest('played_at')
            ->paginate(15);
        return view('admin.matches.index', compact('matches'));
    }

    public function show(TournamentMatch $match)
    {
        $match->load(['homeTeam.players', 'awayTeam.players', 'goals.player', 'group']);
        return view('admin.matches.show', compact('match'));
    }

    public function edit(TournamentMatch $match)
    {
        $match->load(['homeTeam.players', 'awayTeam.players', 'goals']);
        return view('admin.matches.edit', compact('match'));
    }

    public function update(Request $request, TournamentMatch $match)
    {
        $validated = $request->validate([
            'home_score'     => 'required|integer|min:0',
            'away_score'     => 'required|integer|min:0',
            'home_penalties' => 'nullable|integer|min:0',
            'away_penalties' => 'nullable|integer|min:0',
            'goals'          => 'nullable|array',
            'goals.*.player_id' => 'nullable|exists:players,id',
            'goals.*.minute'    => 'required|integer|min:1|max:120',
            'goals.*.type'      => 'required|in:regular,penalty,own_goal',
        ]);

        $isKnockout = in_array($match->stage, ['round32', 'quarter', 'semi', 'final']);
        $isDraw = $validated['home_score'] === $validated['away_score'];

        // Validar que en eliminatoria con empate se requieren penales
        if ($isKnockout && $isDraw) {
            if (is_null($validated['home_penalties']) || is_null($validated['away_penalties'])) {
                return back()->withInput()
                    ->with('error', 'Este es un partido eliminatorio que terminó en empate. Debes registrar el resultado de los penales.');
            }
            if ($validated['home_penalties'] === $validated['away_penalties']) {
                return back()->withInput()
                    ->with('error', 'El resultado de los penales no puede terminar en empate.');
            }
        }

        // Validar coherencia de goles con marcador
        if (!empty($validated['goals'])) {
            $homePlayerIds = $match->homeTeam->players->pluck('id')->toArray();
            $awayPlayerIds = $match->awayTeam->players->pluck('id')->toArray();

            $homeGoals = 0;
            $awayGoals = 0;

            foreach ($validated['goals'] as $goal) {
                $playerId = $goal['player_id'] ?? null;
                $type = $goal['type'];

                if (!$playerId) continue;

                if ($type === 'own_goal') {
                    if (in_array($playerId, $homePlayerIds)) $awayGoals++;
                    else if (in_array($playerId, $awayPlayerIds)) $homeGoals++;
                } else {
                    if (in_array($playerId, $homePlayerIds)) $homeGoals++;
                    else if (in_array($playerId, $awayPlayerIds)) $awayGoals++;
                }
            }

            if ($homeGoals > $validated['home_score'] || $awayGoals > $validated['away_score']) {
                return back()->withInput()
                    ->with('error', 'Los goles registrados no coinciden con el marcador. Verifica los jugadores y tipos de gol.');
            }
        }

        $match->update([
            'home_score'     => $validated['home_score'],
            'away_score'     => $validated['away_score'],
            'home_penalties' => $isDraw && $isKnockout ? $validated['home_penalties'] : null,
            'away_penalties' => $isDraw && $isKnockout ? $validated['away_penalties'] : null,
            'status'         => 'finished',
        ]);

        // Reemplazar goles
        $match->goals()->delete();
        if (!empty($validated['goals'])) {
            foreach ($validated['goals'] as $goal) {
                \App\Models\Goal::create([
                    'match_id'  => $match->id,
                    'player_id' => $goal['player_id'] ?? null,
                    'minute'    => $goal['minute'],
                    'type'      => $goal['type'],
                ]);
            }
        }

        // Notificar a los capitanes
        $match->load(['homeTeam.captain', 'awayTeam.captain', 'tournament']);
        if ($match->homeTeam->captain) {
            \Illuminate\Support\Facades\Mail::to($match->homeTeam->captain->email)
                ->send(new \App\Mail\MatchResultMail($match));
        }
        if ($match->awayTeam->captain) {
            \Illuminate\Support\Facades\Mail::to($match->awayTeam->captain->email)
                ->send(new \App\Mail\MatchResultMail($match));
        }

        return redirect($this->getRedirectUrl($request, $match))
            ->with('success', 'Resultado registrado exitosamente.');
    }

    private function getRedirectUrl(Request $request, TournamentMatch $match): string
    {
        $from = $request->input('from');
        $id   = $request->input('id');
        $url  = route('admin.matches.show', $match);

        if ($from && $id) {
            $url .= "?from={$from}&id={$id}";
        } elseif ($from) {
            $url .= "?from={$from}";
        }

        return $url;
    }

    public function destroy(TournamentMatch $match)
    {
        if ($match->status !== 'scheduled') {
            return back()->with('error', 'Solo se pueden eliminar partidos programados.');
        }
        $match->delete();
        return redirect()->route('admin.matches.index')
            ->with('success', 'Partido eliminado exitosamente.');
    }

    public function create() { return redirect()->route('admin.matches.index'); }
    public function store(Request $request) { return redirect()->route('admin.matches.index'); }

    public function predict(TournamentMatch $match)
    {
        if ($match->status === 'finished') {
            return response()->json(['error' => 'Este partido ya tiene resultado.'], 400);
        }

        $prediction = app(\App\Services\MatchPredictionService::class)->predict($match);

        return response()->json($prediction);
    }
}