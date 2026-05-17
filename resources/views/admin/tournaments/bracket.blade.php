@extends('layouts.admin')

@section('title', 'Bracket — ' . $tournament->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-green-800">🏆 Bracket</h1>
        <p class="text-gray-500 mt-1">{{ $tournament->name }} · {{ $tournament->edition }}</p>
    </div>
    <div class="flex flex-wrap gap-2">
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

@php
    $round32  = $matches['round32'] ?? collect();
    $round16  = $matches['round16'] ?? collect();
    $quarters = $matches['quarter'] ?? collect();
    $semis    = $matches['semi']    ?? collect();
    $final    = $matches['final']   ?? collect();

    $cardH = 88;
    $gap0  = 8;

    // Determinar primera fase activa
    $activeStages = [];
    if ($round32->isNotEmpty()) $activeStages[] = 'round32';
    if ($round16->isNotEmpty()) $activeStages[] = 'round16';
    if ($quarters->isNotEmpty()) $activeStages[] = 'quarter';
    if ($semis->isNotEmpty()) $activeStages[] = 'semi';
    if ($final->isNotEmpty()) $activeStages[] = 'final';

    $firstStage = $activeStages[0] ?? 'final';

    // Gap de la primera fase siempre es $gap0
    // gap(n+1) = gap(n) * 2 + cardH
    $stageOrder = ['round32', 'round16', 'quarter', 'semi', 'final'];
    $firstIdx = array_search($firstStage, $stageOrder);

    $gaps = [];
    $currentGap = $gap0;
    foreach ($stageOrder as $idx => $stage) {
        if ($idx < $firstIdx) {
            $gaps[$stage] = 0;
        } elseif ($idx === $firstIdx) {
            $gaps[$stage] = $currentGap;
        } else {
            $currentGap = $currentGap * 2 + $cardH;
            $gaps[$stage] = $currentGap;
        }
    }

    // PaddingTop: la primera fase tiene 0, cada siguiente tiene la mitad del incremento
    $pt = [];
    foreach ($stageOrder as $idx => $stage) {
        if ($idx <= $firstIdx) {
            $pt[$stage] = 0;
        } else {
            $prevStage = $stageOrder[$idx - 1];
            $pt[$stage] = $pt[$prevStage] + ($cardH + $gaps[$prevStage]) / 2;
        }
    }
@endphp

<div class="bg-white rounded-xl shadow p-6 overflow-auto">
    <div class="flex gap-2 items-start" style="min-width: max-content;">

        {{-- ── RONDA DE 32 ──────────────────────────────── --}}
        @if($round32->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['round32'] }}px;">
            <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Ronda de 32</p>
            <div class="flex flex-col" style="gap: {{ $gaps['round32'] }}px;">
                @foreach($round32->values() as $match)
                    @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                @endforeach
            </div>
        </div>
        <div class="flex flex-col" style="padding-top: {{ $pt['round32'] + 20 }}px; gap: {{ $gaps['round16'] }}px;">
            @foreach($round32->chunk(2) as $i => $pair)
                <div style="height: {{ $cardH }}px; display: flex; align-items: center;">
                    <span class="text-gray-200 text-lg px-1">→</span>
                </div>
            @endforeach
        </div>
        @endif

        {{-- ── OCTAVOS (ROUND16) ────────────────────────── --}}
        @if($round16->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['round16'] }}px;">
            <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Octavos de final</p>
            <div class="flex flex-col" style="gap: {{ $gaps['round16'] }}px;">
                @foreach($round16->values() as $match)
                    @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                @endforeach
            </div>
        </div>
        <div class="flex flex-col" style="padding-top: {{ $pt['round16'] + 20 }}px; gap: {{ $gaps['quarter'] }}px;">
            @foreach($round16->chunk(2) as $pair)
                <div style="height: {{ $cardH }}px; display: flex; align-items: center;">
                    <span class="text-gray-200 text-lg px-1">→</span>
                </div>
            @endforeach
        </div>
        @endif

        {{-- ── CUARTOS ───────────────────────────────────── --}}
        @if($quarters->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['quarter'] }}px;">
            <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Cuartos de final</p>
            <div class="flex flex-col" style="gap: {{ $gaps['quarter'] }}px;">
                @foreach($quarters->values() as $match)
                    @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                @endforeach
            </div>
        </div>
        <div class="flex flex-col" style="padding-top: {{ $pt['quarter'] + 20 }}px; gap: {{ $gaps['semi'] }}px;">
            @foreach($quarters->chunk(2) as $pair)
                <div style="height: {{ $cardH }}px; display: flex; align-items: center;">
                    <span class="text-gray-200 text-lg px-1">→</span>
                </div>
            @endforeach
        </div>
        @endif

        {{-- ── SEMIFINALES ───────────────────────────────── --}}
        @if($semis->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['semi'] }}px;">
            <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Semifinales</p>
            <div class="flex flex-col" style="gap: {{ $gaps['semi'] }}px;">
                @foreach($semis->values() as $match)
                    @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                @endforeach
            </div>
        </div>
        @if($final->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['semi'] + 20 }}px; gap: {{ $gaps['final'] }}px;">
            @foreach($semis->chunk(2) as $pair)
                <div style="height: {{ $cardH }}px; display: flex; align-items: center;">
                    <span class="text-gray-200 text-lg px-1">→</span>
                </div>
            @endforeach
        </div>
        @endif
        @endif

        {{-- ── FINAL + CAMPEÓN ───────────────────────────── --}}
        @if($final->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['final'] }}px;">
            <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Final</p>
            @foreach($final as $match)
                @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'yellow'])

                @if($match->status === 'finished')
                @php
                    $champion = !is_null($match->home_penalties)
                        ? ($match->home_penalties > $match->away_penalties ? $match->homeTeam : $match->awayTeam)
                        : ($match->home_score > $match->away_score ? $match->homeTeam : $match->awayTeam);
                @endphp
                <div class="flex flex-col items-center gap-2 mt-4">
                    @if($champion->shield_url)
                        <img src="{{ Storage::url($champion->shield_url) }}"
                             class="w-16 h-16 rounded-full object-cover border-4 border-yellow-400 shadow-lg">
                    @else
                        <div class="w-16 h-16 rounded-full bg-yellow-100 border-4 border-yellow-400
                                    flex items-center justify-center text-yellow-700 font-bold text-xl shadow-lg">
                            {{ strtoupper(substr($champion->name, 0, 2)) }}
                        </div>
                    @endif
                    <p class="text-2xl">🏆</p>
                    <p class="font-bold text-yellow-700 text-sm text-center">{{ $champion->name }}</p>
                    <p class="text-xs text-gray-400">Campeón</p>
                </div>
                @endif
            @endforeach
        </div>
        @endif

        {{-- Sin partidos --}}
        @if($round32->isEmpty() && $round16->isEmpty() && $quarters->isEmpty() && $semis->isEmpty() && $final->isEmpty())
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