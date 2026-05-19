@extends('layouts.captain')

@section('title', $player->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">👤</span> {{ $player->name }}
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                {{ $team->name }} <span class="text-gray-200 mx-1">•</span> Dorsal #{{ $player->dorsal }} <span class="text-gray-200 mx-1">•</span> {{ \App\Helpers\StatusHelper::position($player->position) }}
            </p>
        </div>
        <a href="{{ route('captain.team.show') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
            ← Volver al equipo
        </a>
    </div>

    {{-- Stats Generales (Módulos numéricos estilizados) --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-center shadow-xs">
            <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Goles totales</p>
            <p class="text-3xl font-black text-emerald-800 mt-1">{{ $totalGoals }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Regulares</p>
            <p class="text-3xl font-black text-gray-700 mt-1">{{ $regularGoals }}</p>
        </div>
        <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 text-center shadow-xs">
            <p class="text-[10px] font-bold text-blue-600 uppercase tracking-wider">Penales</p>
            <p class="text-3xl font-black text-blue-800 mt-1">{{ $penaltyGoals }}</p>
        </div>
        <div class="bg-red-50/60 border border-red-100 rounded-2xl p-4 text-center shadow-xs">
            <p class="text-[10px] font-bold text-red-600 uppercase tracking-wider">Autogoles</p>
            <p class="text-3xl font-black text-red-800 mt-1">{{ $ownGoals }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 text-center shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Partidos jugados</p>
            <p class="text-3xl font-black text-gray-700 mt-1">{{ $matchesPlayed }}</p>
        </div>
    </div>

    {{-- Torneos ganados --}}
    @if($tournamentsWon->isNotEmpty())
    <div class="bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl p-5 shadow-xs">
        <h2 class="font-bold text-amber-950 text-xs uppercase tracking-wider mb-3 flex items-center gap-1">🏆 Torneos ganados con {{ $team->name }}</h2>
        <div class="flex flex-wrap gap-2">
            @foreach($tournamentsWon as $tournament)
                <span class="bg-white border border-amber-300/60 text-amber-800 px-3 py-1 rounded-full text-xs font-bold shadow-xs">
                    {{ $tournament->name }} {{ $tournament->edition }}
                </span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Goles por torneo --}}
    @if($goalsByTournament->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4">Goles por torneo</h2>
        <div class="flex flex-wrap gap-3">
            @foreach($goalsByTournament as $tournamentName => $count)
                <div class="flex items-center gap-2 bg-slate-50 border border-gray-200/80 rounded-xl px-4 py-2 shadow-xs font-medium">
                    <span class="text-green-700 font-black text-lg">{{ $count }}</span>
                    <span class="text-gray-600 text-xs">{{ $tournamentName }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Historial de goles --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-50">
            <h2 class="text-base font-bold text-gray-800 tracking-tight">Historial de goles</h2>
        </div>

        @if($player->goals->isEmpty())
            <div class="px-6 py-10 text-center text-sm font-medium text-gray-400">
                Este jugador no ha marcado goles aún.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs md:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-[10px] md:text-xs">
                        <tr>
                            <th class="px-6 py-4">Torneo</th>
                            <th class="px-6 py-4">Partido</th>
                            <th class="px-6 py-4">Fase</th>
                            <th class="px-6 py-4 text-center">Minuto</th>
                            <th class="px-6 py-4 text-center">Tipo</th>
                            <th class="px-6 py-4 text-center">Resultado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($player->goals->sortByDesc(fn($g) => $g->match->played_at) as $goal)
                        @php
                            $match = $goal->match;
                            $isHome = $match->home_team_id === $team->id;
                            $myScore  = $isHome ? $match->home_score : $match->away_score;
                            $oppScore = $isHome ? $match->away_score : $match->home_score;
                            $opponent = $isHome ? $match->awayTeam->name : $match->homeTeam->name;
                            $result = $myScore > $oppScore ? 'V' : ($myScore < $oppScore ? 'D' : 'E');
                            $resultColor = $result === 'V' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' :
                                          ($result === 'D' ? 'bg-rose-50 text-rose-700 border border-rose-100' : 'bg-gray-50 text-gray-500 border border-gray-200');
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors font-medium text-gray-600">
                            <td class="px-6 py-4 text-gray-400 font-semibold">{{ $match->tournament->name }}</td>
                            <td class="px-6 py-4 font-bold text-gray-700">
                                {{ $team->name }} <span class="text-gray-300 font-normal">vs</span> {{ $opponent }}
                            </td>
                            <td class="px-6 py-4 text-xs font-semibold">
                                {{ \App\Helpers\StatusHelper::stage($match->stage) }}
                            </td>
                            <td class="px-6 py-4 text-center font-mono font-black text-green-700 text-sm">
                                {{ $goal->minute }}'
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide
                                    {{ $goal->type === 'regular' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                                    {{ $goal->type === 'penalty' ? 'bg-blue-50 text-blue-700 border border-blue-100' : '' }}
                                    {{ $goal->type === 'own_goal' ? 'bg-rose-50 text-rose-700 border border-rose-100' : '' }}">
                                    {{ \App\Helpers\StatusHelper::goalType($goal->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-bold font-mono tracking-tight shadow-xs {{ $resultColor }}">
                                    {{ $result }} {{ $myScore }}-{{ $oppScore }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection