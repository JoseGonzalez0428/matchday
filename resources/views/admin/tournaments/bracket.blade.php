@extends('layouts.admin')

@section('title', 'Bracket — ' . $tournament->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-green-800">🏆 Bracket</h1>
        <p class="text-gray-500 mt-1">{{ $tournament->name }} · {{ $tournament->edition }}</p>
    </div>
    <div class="flex gap-2">
        {{-- PDF Bracket - próximamente --}}
<button disabled
        class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm cursor-not-allowed">
    📄 PDF Bracket
</button>
        <a href="{{ route('admin.tournaments.show', $tournament) }}"
           class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
            ← Volver
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6 overflow-x-auto">
    <div class="flex gap-8 min-w-max">

        {{-- CUARTOS DE FINAL --}}
        @if(isset($matches['quarter']))
        <div class="flex flex-col justify-around gap-4">
            <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Cuartos de final</p>
            @foreach($matches['quarter'] as $match)
                <div class="w-52 border rounded-xl overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between px-3 py-2
                        {{ $match->status === 'finished' && $match->home_score > $match->away_score ? 'bg-green-50' : 'bg-white' }}
                        border-b">
                        <span class="text-sm font-medium truncate">
                            {{ $match->homeTeam->name }}
                        </span>
                        <span class="text-sm font-bold ml-2 {{ $match->status === 'finished' && $match->home_score > $match->away_score ? 'text-green-700' : 'text-gray-600' }}">
                            {{ $match->status === 'finished' ? $match->home_score : '?' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2
                        {{ $match->status === 'finished' && $match->away_score > $match->home_score ? 'bg-green-50' : 'bg-white' }}">
                        <span class="text-sm font-medium truncate">
                            {{ $match->awayTeam->name }}
                        </span>
                        <span class="text-sm font-bold ml-2 {{ $match->status === 'finished' && $match->away_score > $match->home_score ? 'text-green-700' : 'text-gray-600' }}">
                            {{ $match->status === 'finished' ? $match->away_score : '?' }}
                        </span>
                    </div>
                    <div class="px-3 py-1 bg-gray-50 text-xs text-gray-400 text-center border-t">
                        {{ $match->status === 'finished' ? '✅ Finalizado' : '🕐 ' . $match->played_at->format('d/m H:i') }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FLECHA --}}
        @if(isset($matches['semi']))
        <div class="flex items-center text-gray-300 text-2xl">→</div>
        @endif
        @endif

        {{-- SEMIFINALES --}}
        @if(isset($matches['semi']))
        <div class="flex flex-col justify-around gap-4">
            <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Semifinales</p>
            @foreach($matches['semi'] as $match)
                <div class="w-52 border rounded-xl overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between px-3 py-2
                        {{ $match->status === 'finished' && $match->home_score > $match->away_score ? 'bg-green-50' : 'bg-white' }}
                        border-b">
                        <span class="text-sm font-medium truncate">
                            {{ $match->homeTeam->name }}
                        </span>
                        <span class="text-sm font-bold ml-2 {{ $match->status === 'finished' && $match->home_score > $match->away_score ? 'text-green-700' : 'text-gray-600' }}">
                            {{ $match->status === 'finished' ? $match->home_score : '?' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2
                        {{ $match->status === 'finished' && $match->away_score > $match->home_score ? 'bg-green-50' : 'bg-white' }}">
                        <span class="text-sm font-medium truncate">
                            {{ $match->awayTeam->name }}
                        </span>
                        <span class="text-sm font-bold ml-2 {{ $match->status === 'finished' && $match->away_score > $match->home_score ? 'text-green-700' : 'text-gray-600' }}">
                            {{ $match->status === 'finished' ? $match->away_score : '?' }}
                        </span>
                    </div>
                    <div class="px-3 py-1 bg-gray-50 text-xs text-gray-400 text-center border-t">
                        {{ $match->status === 'finished' ? '✅ Finalizado' : '🕐 ' . $match->played_at->format('d/m H:i') }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- FLECHA --}}
        @if(isset($matches['final']))
        <div class="flex items-center text-gray-300 text-2xl">→</div>
        @endif
        @endif

        {{-- FINAL --}}
        @if(isset($matches['final']))
        <div class="flex flex-col justify-center gap-4">
            <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Final</p>
            @foreach($matches['final'] as $match)
                <div class="w-52 border-2 border-yellow-400 rounded-xl overflow-hidden shadow-lg">
                    <div class="flex items-center justify-between px-3 py-2
                        {{ $match->status === 'finished' && $match->home_score > $match->away_score ? 'bg-yellow-50' : 'bg-white' }}
                        border-b">
                        <span class="text-sm font-bold truncate">
                            {{ $match->homeTeam->name }}
                            @if($match->status === 'finished' && $match->home_score > $match->away_score)
                                🏆
                            @endif
                        </span>
                        <span class="text-sm font-bold ml-2 {{ $match->status === 'finished' && $match->home_score > $match->away_score ? 'text-yellow-600' : 'text-gray-600' }}">
                            {{ $match->status === 'finished' ? $match->home_score : '?' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between px-3 py-2
                        {{ $match->status === 'finished' && $match->away_score > $match->home_score ? 'bg-yellow-50' : 'bg-white' }}">
                        <span class="text-sm font-bold truncate">
                            {{ $match->awayTeam->name }}
                            @if($match->status === 'finished' && $match->away_score > $match->home_score)
                                🏆
                            @endif
                        </span>
                        <span class="text-sm font-bold ml-2 {{ $match->status === 'finished' && $match->away_score > $match->home_score ? 'text-yellow-600' : 'text-gray-600' }}">
                            {{ $match->status === 'finished' ? $match->away_score : '?' }}
                        </span>
                    </div>
                    <div class="px-3 py-1 bg-yellow-50 text-xs text-yellow-600 text-center border-t font-medium">
                        {{ $match->status === 'finished' ? '🏆 Final jugada' : '🕐 ' . $match->played_at->format('d/m H:i') }}
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Sin partidos eliminatorios --}}
        @if(!isset($matches['quarter']) && !isset($matches['semi']) && !isset($matches['final']))
        <div class="flex items-center justify-center w-full py-16 text-gray-400">
            <div class="text-center">
                <p class="text-5xl mb-4">⏳</p>
                <p class="text-lg font-medium">Aún no hay fase eliminatoria</p>
                <p class="text-sm mt-1">Completa la fase de grupos y genera la siguiente fase.</p>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection