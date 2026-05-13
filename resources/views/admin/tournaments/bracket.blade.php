@extends('layouts.admin')

@section('title', 'Bracket — ' . $tournament->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-green-800">🏆 Bracket</h1>
        <p class="text-gray-500 mt-1">{{ $tournament->name }} · {{ $tournament->edition }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.tournaments.pdf.bracket', $tournament) }}"
            class="bg-gray-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-800">
                📄 PDF Bracket
        </a>
        <a href="{{ route('admin.tournaments.show', $tournament) }}"
           class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
            ← Volver
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6 overflow-x-auto">
@php
    $quarters = $matches['quarter'] ?? collect();
    $semis    = $matches['semi']    ?? collect();
    $final    = $matches['final']   ?? collect();

    // Emparejar cuartos con su semi correspondiente
    // C1+C2 → Semi1, C3+C4 → Semi2
    $quarterPairs = $quarters->values()->chunk(2);
@endphp

<div class="flex gap-0 min-w-max items-stretch">

    {{-- ── CUARTOS ──────────────────────────── --}}
    @if($quarters->isNotEmpty())
    <div class="flex flex-col justify-around" style="gap: 0;">
        <p class="text-xs font-bold text-gray-400 uppercase text-center mb-4">Cuartos de final</p>
        <div class="flex flex-col" style="gap: 48px;">
            @foreach($quarterPairs as $pairIndex => $pair)
                <div class="flex flex-col" style="gap: 8px;">
                    @foreach($pair as $match)
                        @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    {{-- Conectores cuartos → semis --}}
    <div class="flex flex-col justify-around mx-3" style="gap: 0;">
        <div style="height: 20px;"></div>
        <div class="flex flex-col" style="gap: 48px;">
            @foreach($quarterPairs as $pair)
                <div class="flex items-center" style="height: {{ count($pair) * 80 + 8 }}px;">
                    <span class="text-gray-300 text-xl">→</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── SEMIFINALES ──────────────────────── --}}
    @if($semis->isNotEmpty())
    <div class="flex flex-col" style="gap: 0;">
        <p class="text-xs font-bold text-gray-400 uppercase text-center mb-4">Semifinales</p>
        <div class="flex flex-col justify-around h-full" style="gap: 48px; padding-top: {{ $quarters->isNotEmpty() ? '36px' : '0' }};">
            @foreach($semis->values() as $match)
                @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
            @endforeach
        </div>
    </div>

    {{-- Conectores semis → final --}}
    @if($final->isNotEmpty())
    <div class="flex flex-col justify-around mx-3" style="gap: 0;">
        <div style="height: 20px;"></div>
        <div class="flex flex-col justify-around h-full" style="gap: 48px; padding-top: 36px;">
            @foreach($semis->chunk(2) as $chunk)
                <div class="flex items-center" style="height: {{ count($chunk) * 80 + 8 }}px;">
                    <span class="text-gray-300 text-xl">→</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    @endif

    {{-- ── FINAL ────────────────────────────── --}}
    @if($final->isNotEmpty())
    <div class="flex flex-col justify-center" style="gap: 0;">
        <p class="text-xs font-bold text-gray-400 uppercase text-center mb-4">Final</p>
        <div class="flex items-center gap-6 h-full" style="padding-top: {{ $semis->isNotEmpty() ? '36px' : '0' }};">
            @foreach($final as $match)
                @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'yellow'])

                {{-- Campeón --}}
                @if($match->status === 'finished')
                    @php
                        $champion = !is_null($match->home_penalties)
                            ? ($match->home_penalties > $match->away_penalties ? $match->homeTeam : $match->awayTeam)
                            : ($match->home_score > $match->away_score ? $match->homeTeam : $match->awayTeam);
                    @endphp
                    <div class="flex flex-col items-center gap-3 ml-4">
                        @if($champion->shield_url)
                            <img src="{{ Storage::url($champion->shield_url) }}"
                                class="w-20 h-20 rounded-full object-cover border-4 border-yellow-400 shadow-lg">
                        @else
                            <div class="w-20 h-20 rounded-full bg-yellow-100 border-4 border-yellow-400 
                                        flex items-center justify-center text-yellow-700 font-bold text-2xl shadow-lg">
                                {{ strtoupper(substr($champion->name, 0, 2)) }}
                            </div>
                        @endif
                        <div class="text-center">
                            <p class="text-2xl">🏆</p>
                            <p class="font-bold text-yellow-700 text-sm mt-1">{{ $champion->name }}</p>
                            <p class="text-xs text-gray-400">Campeón</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    {{-- Sin partidos eliminatorios --}}
    @if($quarters->isEmpty() && $semis->isEmpty() && $final->isEmpty())
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