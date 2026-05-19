@extends('layouts.admin')

@section('title', 'Detalle del Partido')

@section('content')

{{-- Navbar de Navegación / Acciones --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <div>
        @if(request('from') === 'tournament' && request('id'))
            <a href="{{ route('admin.tournaments.show', request('id')) }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-green-700 transition-colors bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver al torneo
            </a>
        @else
            <a href="{{ route('admin.matches.index') }}"
               class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-green-700 transition-colors bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Volver a partidos
            </a>
        @endif
    </div>

    <a href="{{ route('admin.matches.edit', $match) }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-white px-5 py-2.5 rounded-lg shadow-sm transition-all {{ $match->status !== 'finished' ? 'bg-green-600 hover:bg-green-700' : 'bg-gray-700 hover:bg-gray-800' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
        {{ $match->status !== 'finished' ? 'Cargar resultado' : 'Editar resultado' }}
    </a>
</div>

{{-- Variables de Estado del Partido (Mantenemos tu lógica limpia) --}}
@php 
    $isFinished = $match->status === 'finished';
    $hasPenalties = !is_null($match->home_penalties);
    $homeWins = $isFinished && ($hasPenalties ? $match->home_penalties > $match->away_penalties : $match->home_score > $match->away_score);
    $awayWins = $isFinished && !$homeWins && ($hasPenalties ? $match->away_penalties > $match->home_penalties : $match->away_score > $match->home_score);
    $isDraw = $isFinished && $match->home_score === $match->away_score && !$hasPenalties;
@endphp

{{-- Tarjeta Principal del Partido --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 overflow-hidden">
    
    {{-- Encabezado del Torneo --}}
    <div class="bg-gradient-to-r from-green-800 to-green-700 text-white px-6 py-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
        <div class="flex items-center gap-2">
            <span class="font-semibold tracking-wide text-base">{{ $match->tournament->name }}</span>
            <span class="text-xs bg-green-900/50 px-2 py-0.5 rounded-full border border-green-600 text-green-200">
                {{ $match->group ? 'Grupo ' . $match->group->name : \App\Helpers\StatusHelper::stage($match->stage) }}
            </span>
        </div>
        <div class="flex items-center gap-1.5 text-sm text-green-100 font-medium">
            <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            {{ $match->played_at->format('d/m/Y H:i') }} hs
        </div>
    </div>

    {{-- Bloque del Marcador En Vivo/Terminado --}}
    <div class="p-6 md:p-10 bg-slate-50/50">
        <div class="grid grid-cols-1 md:grid-cols-7 items-center gap-6 md:gap-2">
            
            {{-- Local --}}
            <div class="md:col-span-3 flex flex-col items-center p-4 rounded-xl transition-all {{ $homeWins ? 'bg-green-50/60 border border-green-100 shadow-sm' : '' }}">
                <div class="relative">
                    @if($match->homeTeam->shield_url)
                        <img src="{{ Storage::url($match->homeTeam->shield_url) }}" class="w-20 h-20 rounded-full object-cover shadow-sm bg-white border p-1">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-500 border shadow-inner">
                            {{ strtoupper(substr($match->homeTeam->name, 0, 2)) }}
                        </div>
                    @endif
                    @if($homeWins)
                        <span class="absolute -top-1 -right-1 bg-yellow-400 text-white p-1 rounded-full shadow border border-white animate-bounce">🏆</span>
                    @endif
                </div>
                <h3 class="mt-3 text-lg font-bold text-center {{ $homeWins ? 'text-green-800' : 'text-gray-800' }}">
                    {{ $match->homeTeam->name }}
                </h3>
                <span class="text-xs font-semibold tracking-wider text-gray-400 uppercase mt-1">Local</span>
            </div>

            {{-- Centro: Resultado --}}
            <div class="md:col-span-1 flex flex-col items-center justify-center min-h-[120px]">
                @if($isFinished)
                    <div class="flex items-center gap-3 bg-white px-5 py-2 rounded-xl shadow-sm border border-gray-100">
                        <span class="text-4xl font-black text-gray-800 tracking-tight">{{ $match->home_score }}</span>
                        <span class="text-xl font-bold text-gray-300">—</span>
                        <span class="text-4xl font-black text-gray-800 tracking-tight">{{ $match->away_score }}</span>
                    </div>

                    {{-- Penales si existieran --}}
                    @if($hasPenalties)
                        <div class="mt-3 text-center bg-blue-50 border border-blue-100 px-3 py-1 rounded-lg">
                            <p class="text-xs font-bold text-blue-700 uppercase tracking-wide">Penales</p>
                            <p class="text-sm font-black text-blue-800">{{ $match->home_penalties }} - {{ $match->away_penalties }}</p>
                        </div>
                    @endif

                    <span class="mt-4 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase bg-emerald-100 text-emerald-800 flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Finalizado
                    </span>
                @else
                    <div class="bg-gray-200/60 text-gray-400 text-sm font-black tracking-widest px-4 py-2 rounded-lg uppercase border border-gray-300/40">
                        VS
                    </div>
                    <span class="mt-4 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase bg-amber-100 text-amber-800 flex items-center gap-1 animate-pulse">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Programado
                    </span>
                @endif
            </div>

            {{-- Visitante --}}
            <div class="md:col-span-3 flex flex-col items-center p-4 rounded-xl transition-all {{ $awayWins ? 'bg-green-50/60 border border-green-100 shadow-sm' : '' }}">
                <div class="relative">
                    @if($match->awayTeam->shield_url)
                        <img src="{{ Storage::url($match->awayTeam->shield_url) }}" class="w-20 h-20 rounded-full object-cover shadow-sm bg-white border p-1">
                    @else
                        <div class="w-20 h-20 rounded-full bg-gray-200 flex items-center justify-center text-2xl font-bold text-gray-500 border shadow-inner">
                            {{ strtoupper(substr($match->awayTeam->name, 0, 2)) }}
                        </div>
                    @endif
                    @if($awayWins)
                        <span class="absolute -top-1 -right-1 bg-yellow-400 text-white p-1 rounded-full shadow border border-white animate-bounce">🏆</span>
                    @endif
                </div>
                <h3 class="mt-3 text-lg font-bold text-center {{ $awayWins ? 'text-green-800' : 'text-gray-800' }}">
                    {{ $match->awayTeam->name }}
                </h3>
                <span class="text-xs font-semibold tracking-wider text-gray-400 uppercase mt-1">Visitante</span>
            </div>

        </div>
    </div>
</div>

{{-- Bloque Cronológico de Goles (Estilo Línea de Tiempo Profesional) --}}
@if($match->goals->isNotEmpty())
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div class="flex items-center gap-2 border-b border-gray-100 pb-4 mb-4">
        <span class="text-xl">⚽</span>
        <h2 class="text-base font-bold text-gray-800 tracking-tight">Goles e Incidencias</h2>
    </div>

    @php 
        $allGoals = $match->goals->map(function($goal) use ($match) {
            // Determinar si el gol suma al local o al visitante
            $isOwnGoal = $goal->type === 'own_goal';
            $playerTeamId = $goal->player?->team_id;
            $goal->is_home_side = $isOwnGoal ? $playerTeamId !== $match->home_team_id : $playerTeamId === $match->home_team_id;
            return $goal;
        })->sortBy('minute');
    @endphp

    <div class="relative before:absolute before:inset-0 before:left-1/2 before:-ml-px before:h-full before:w-0.5 before:bg-gray-100 before:hidden md:before:block space-y-3">
        @foreach($allGoals as $goal)
            <div class="grid grid-cols-1 md:grid-cols-7 items-center relative">
                
                {{-- Evento de Gol Local --}}
                <div class="md:col-span-3 text-right order-2 md:order-1 {{ $goal->is_home_side ? 'opacity-100' : 'opacity-0 hidden md:block' }}">
                    @if($goal->is_home_side)
                        <div class="flex items-center justify-end gap-2.5">
                            <span class="text-xs font-semibold px-2 py-0.5 rounded
                                {{ $goal->type === 'regular' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $goal->type === 'penalty' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                {{ $goal->type === 'own_goal' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}">
                                {{ \App\Helpers\StatusHelper::goalType($goal->type) }}
                            </span>
                            <span class="text-sm font-medium text-gray-700">{{ $goal->player->name ?? 'Desconocido' }}</span>
                        </div>
                    @endif
                </div>

                {{-- Minuto Central --}}
                <div class="md:col-span-1 flex justify-start md:justify-center items-center order-1 md:order-2 my-1 md:my-0">
                    <span class="z-10 bg-white border border-gray-200 text-gray-500 text-xs font-bold px-2.5 py-1 rounded-full shadow-sm">
                        {{ $goal->minute }}'
                    </span>
                </div>

                {{-- Evento de Gol Visitante --}}
                <div class="md:col-span-3 text-left order-3 {{ !$goal->is_home_side ? 'opacity-100' : 'opacity-0 hidden md:block' }}">
                    @if(!$goal->is_home_side)
                        <div class="flex items-center justify-start gap-2.5">
                            <span class="text-sm font-medium text-gray-700">{{ $goal->player->name ?? 'Desconocido' }}</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded
                                {{ $goal->type === 'regular' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : '' }}
                                {{ $goal->type === 'penalty' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                                {{ $goal->type === 'own_goal' ? 'bg-rose-50 text-rose-700 border border-rose-200' : '' }}">
                                {{ \App\Helpers\StatusHelper::goalType($goal->type) }}
                            </span>
                        </div>
                    @endif
                </div>

            </div>
        @endforeach
    </div>
</div>
@endif

@endsection