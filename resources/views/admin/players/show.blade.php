@extends('layouts.admin')

@section('title', $player->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-green-800">👤 {{ $player->name }}</h1>
        <p class="text-gray-500 mt-1">
            {{ $team->name }} · Dorsal #{{ $player->dorsal }} ·
            {{ \App\Helpers\StatusHelper::position($player->position) }}
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.teams.players.edit', [$team, $player]) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Editar
        </a>
        <a href="{{ route('admin.teams.players.index', $team) }}"
           class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
            ← Volver
        </a>
    </div>
</div>

{{-- Stats generales --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Goles totales</p>
        <p class="text-4xl font-bold text-green-700 mt-1">{{ $totalGoals }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Regulares</p>
        <p class="text-4xl font-bold text-gray-700 mt-1">{{ $regularGoals }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Penales</p>
        <p class="text-4xl font-bold text-blue-600 mt-1">{{ $penaltyGoals }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Autogoles</p>
        <p class="text-4xl font-bold text-red-500 mt-1">{{ $ownGoals }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4 text-center">
        <p class="text-xs text-gray-500 uppercase">Partidos jugados</p>
        <p class="text-4xl font-bold text-gray-700 mt-1">{{ $matchesPlayed }}</p>
    </div>
</div>

{{-- Torneos ganados --}}
@if($tournamentsWon->isNotEmpty())
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
    <h2 class="font-bold text-yellow-700 mb-3">🏆 Torneos ganados con {{ $team->name }}</h2>
    <div class="flex flex-wrap gap-2">
        @foreach($tournamentsWon as $tournament)
            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-medium">
                {{ $tournament->name }} {{ $tournament->edition }}
            </span>
        @endforeach
    </div>
</div>
@endif

{{-- Goles por torneo --}}
@if($goalsByTournament->isNotEmpty())
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">Goles por torneo</h2>
    <div class="flex flex-wrap gap-3">
        @foreach($goalsByTournament as $tournamentName => $count)
            <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                <span class="text-green-700 font-bold text-lg">{{ $count }}</span>
                <span class="text-gray-600 text-sm">{{ $tournamentName }}</span>
            </div>
        @endforeach
    </div>
</div>
@endif

{{-- Historial de goles --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-bold text-gray-700">Historial de goles</h2>
    </div>

    @if($player->goals->isEmpty())
        <div class="px-6 py-8 text-center text-gray-400">
            Este jugador no ha marcado goles aún.
        </div>
    @else
        <table class="w-full text-sm">
            <thead class="bg-green-50 text-green-800">
                <tr>
                    <th class="text-left px-4 py-3">Torneo</th>
                    <th class="text-left px-4 py-3">Partido</th>
                    <th class="text-left px-4 py-3">Fase</th>
                    <th class="text-center px-4 py-3">Minuto</th>
                    <th class="text-center px-4 py-3">Tipo</th>
                    <th class="text-center px-4 py-3">Resultado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($player->goals->sortByDesc(fn($g) => $g->match->played_at) as $goal)
                @php
                    $match = $goal->match;
                    $isHome = $match->home_team_id === $team->id;
                    $myScore  = $isHome ? $match->home_score : $match->away_score;
                    $oppScore = $isHome ? $match->away_score : $match->home_score;
                    $opponent = $isHome ? $match->awayTeam->name : $match->homeTeam->name;
                    $result = $myScore > $oppScore ? 'V' : ($myScore < $oppScore ? 'D' : 'E');
                    $resultColor = $result === 'V' ? 'bg-green-100 text-green-700' :
                                  ($result === 'D' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600');
                @endphp
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-500">{{ $match->tournament->name }}</td>
                    <td class="px-4 py-3 font-medium">
                        {{ $team->name }} vs {{ $opponent }}
                    </td>
                    <td class="px-4 py-3">
                        {{ \App\Helpers\StatusHelper::stage($match->stage) }}
                    </td>
                    <td class="px-4 py-3 text-center font-bold text-green-700">
                        {{ $goal->minute }}'
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded text-xs font-medium
                            {{ $goal->type === 'regular' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $goal->type === 'penalty' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $goal->type === 'own_goal' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ \App\Helpers\StatusHelper::goalType($goal->type) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $resultColor }}">
                            {{ $result }} {{ $myScore }}-{{ $oppScore }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection