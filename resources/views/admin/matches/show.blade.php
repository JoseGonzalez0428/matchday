@extends('layouts.admin')

@section('title', 'Detalle del Partido')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-3">
        @if(request('from') === 'tournament' && request('id'))
            <a href="{{ route('admin.tournaments.show', request('id')) }}"
            class="text-gray-500 hover:text-gray-700 border rounded-lg px-3 py-2 text-sm hover:bg-gray-50">
                ← Volver al torneo
            </a>
        @else
            <a href="{{ route('admin.matches.index') }}"
            class="text-gray-500 hover:text-gray-700 border rounded-lg px-3 py-2 text-sm hover:bg-gray-50">
                ← Volver a partidos
            </a>
        @endif
        <h1 class="text-3xl font-bold text-green-800">📅 Detalle del Partido</h1>
    </div>
    @if($match->status !== 'finished')
        <a href="{{ route('admin.matches.edit', $match) }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Cargar resultado
        </a>
    @else
        <a href="{{ route('admin.matches.edit', $match) }}"
        class="bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">
            Editar resultado
        </a>
    @endif
</div>

{{-- Resultado --}}
<div class="bg-white rounded-xl shadow p-8 mb-6">
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="text-center w-1/3">
            <p class="text-2xl font-bold text-gray-800">{{ $match->homeTeam->name }}</p>
            <p class="text-sm text-gray-400">Local</p>
        </div>
        <div class="text-center">
            @if($match->status === 'finished')
            <p class="text-4xl md:text-5xl font-bold text-green-700">
                {{ $match->home_score }} — {{ $match->away_score }}
            </p>
            @if(!is_null($match->home_penalties))
                <p class="text-sm text-blue-600 mt-1 font-medium">
                    Penales: {{ $match->home_penalties }} — {{ $match->away_penalties }}
                </p>
                <p class="text-xs text-gray-400 mt-1">
                    @if($match->home_penalties > $match->away_penalties)
                        {{ $match->homeTeam->name }} avanza por penales
                    @else
                        {{ $match->awayTeam->name }} avanza por penales
                    @endif
                </p>
            @endif
        @else
                <p class="text-2xl font-bold text-gray-400">vs</p>
                <p class="text-sm text-gray-400 mt-1">{{ $match->played_at->format('d/m/Y H:i') }}</p>
            @endif
            <span class="mt-2 inline-block px-3 py-1 rounded-full text-xs font-medium
                {{ $match->status === 'finished' ? 'bg-green-100 text-green-700' : '' }}
                {{ $match->status === 'scheduled' ? 'bg-yellow-100 text-yellow-700' : '' }}
                {{ $match->status === 'live' ? 'bg-red-100 text-red-700' : '' }}">
                {{ \App\Helpers\StatusHelper::match($match->status) }}
            </span>
        </div>
        <div class="text-center md:w-1/3">
            <p class="text-xl md:text-2xl font-bold text-gray-800">{{ $match->awayTeam->name }}</p>
            <p class="text-sm text-gray-400">Visitante</p>
        </div>
    </div>
</div>

{{-- Goles --}}
@if($match->goals->isNotEmpty())
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">⚽ Goles</h2>
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="bg-green-50 text-green-800">
            <tr>
                <th class="text-left px-4 py-2">Jugador</th>
                <th class="text-left px-4 py-2">Minuto</th>
                <th class="text-left px-4 py-2">Tipo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($match->goals->sortBy('minute') as $goal)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $goal->player->name ?? 'Desconocido' }}</td>
                <td class="px-4 py-2">{{ $goal->minute }}'</td>
                <td class="px-4 py-2">
                    <span class="px-2 py-1 rounded text-xs
                        {{ $goal->type === 'regular' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $goal->type === 'penalty' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $goal->type === 'own_goal' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ \App\Helpers\StatusHelper::goalType($goal->type) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table></div>
</div>
@endif

{{-- Info adicional --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-gray-50 rounded-xl p-4">
        <p class="text-xs text-gray-400 uppercase">Torneo</p>
        <p class="font-bold text-gray-800">{{ $match->tournament->name }}</p>
    </div>
    <div class="bg-gray-50 rounded-xl p-4">
        <p class="text-xs text-gray-400 uppercase">Grupo / Fase</p>
        <p class="font-bold text-gray-800">
            {{ $match->group ? 'Grupo ' . $match->group->name : \App\Helpers\StatusHelper::stage($match->stage) }}
        </p>
    </div>
    <div class="bg-gray-50 rounded-xl p-4">
        <p class="text-xs text-gray-400 uppercase">Fecha</p>
        <p class="font-bold text-gray-800">{{ $match->played_at->format('d/m/Y H:i') }}</p>
    </div>
</div>
@endsection