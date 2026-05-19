@extends('layouts.admin')

@section('title', 'Bracket — ' . $tournament->name)

@section('content')

{{-- Encabezado Principal --}}
<div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 mb-6 pb-5 border-b border-gray-100">
    <div>
        <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
            <span class="text-green-600">🏆</span> Bracket
        </h1>
        <p class="text-sm font-medium text-gray-400 mt-1">
            {{ $tournament->name }} <span class="text-gray-200 mx-1">•</span> Edición {{ $tournament->edition }}
        </p>
    </div>
    <div class="flex flex-wrap items-center justify-center gap-2.5 w-full sm:w-auto">
        <a href="{{ route('admin.tournaments.pdf.bracket', $tournament) }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-gray-700 hover:bg-gray-800 text-white font-semibold text-xs px-4 py-2.5 rounded-xl shadow-sm transition-all">
            📄 PDF Bracket
        </a>
        <a href="{{ route('admin.tournaments.show', $tournament) }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
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

{{-- Lienzo del Arbol de Eliminación Directa --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 overflow-auto custom-scrollbar">
    <div class="flex gap-4 items-start" style="min-width: max-content;">

        {{-- ── RONDA DE 32 ──────────────────────────────── --}}
        @if($round32->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['round32'] }}px;">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center bg-slate-50 border rounded-lg py-1 mb-4 shadow-xs">Ronda de 32</p>
            <div class="flex flex-col" style="gap: {{ $gaps['round32'] }}px;">
                @foreach($round32->values() as $match)
                    @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                @endforeach
            </div>
        </div>
        <div class="flex flex-col animate-pulse" style="padding-top: {{ $pt['round32'] + 28 }}px; gap: {{ $gaps['round16'] }}px;">
            @foreach($round32->chunk(2) as $i => $pair)
                <div style="height: {{ $cardH }}px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-gray-300 font-mono font-bold text-base bg-slate-50 border border-gray-100 px-2 py-0.5 rounded-md shadow-inner">→</span>
                </div>
            @endforeach
        </div>
        @endif

        {{-- ── OCTAVOS (ROUND16) ────────────────────────── --}}
        @if($round16->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['round16'] }}px;">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center bg-slate-50 border rounded-lg py-1 mb-4 shadow-xs">Octavos de final</p>
            <div class="flex flex-col" style="gap: {{ $gaps['round16'] }}px;">
                @foreach($round16->values() as $match)
                    @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                @endforeach
            </div>
        </div>
        <div class="flex flex-col animate-pulse" style="padding-top: {{ $pt['round16'] + 28 }}px; gap: {{ $gaps['quarter'] }}px;">
            @foreach($round16->chunk(2) as $pair)
                <div style="height: {{ $cardH }}px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-gray-300 font-mono font-bold text-base bg-slate-50 border border-gray-100 px-2 py-0.5 rounded-md shadow-inner">→</span>
                </div>
            @endforeach
        </div>
        @endif

        {{-- ── CUARTOS ───────────────────────────────────── --}}
        @if($quarters->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['quarter'] }}px;">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center bg-slate-50 border rounded-lg py-1 mb-4 shadow-xs">Cuartos de final</p>
            <div class="flex flex-col" style="gap: {{ $gaps['quarter'] }}px;">
                @foreach($quarters->values() as $match)
                    @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                @endforeach
            </div>
        </div>
        <div class="flex flex-col animate-pulse" style="padding-top: {{ $pt['quarter'] + 28 }}px; gap: {{ $gaps['semi'] }}px;">
            @foreach($quarters->chunk(2) as $pair)
                <div style="height: {{ $cardH }}px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-gray-300 font-mono font-bold text-base bg-slate-50 border border-gray-100 px-2 py-0.5 rounded-md shadow-inner">→</span>
                </div>
            @endforeach
        </div>
        @endif

        {{-- ── SEMIFINALES ───────────────────────────────── --}}
        @if($semis->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['semi'] }}px;">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center bg-slate-50 border rounded-lg py-1 mb-4 shadow-xs">Semifinales</p>
            <div class="flex flex-col" style="gap: {{ $gaps['semi'] }}px;">
                @foreach($semis->values() as $match)
                    @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'green'])
                @endforeach
            </div>
        </div>
        @if($final->isNotEmpty())
        <div class="flex flex-col animate-pulse" style="padding-top: {{ $pt['semi'] + 28 }}px; gap: {{ $gaps['final'] }}px;">
            @foreach($semis->chunk(2) as $pair)
                <div style="height: {{ $cardH }}px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-gray-300 font-mono font-bold text-base bg-slate-50 border border-gray-100 px-2 py-0.5 rounded-md shadow-inner">→</span>
                </div>
            @endforeach
        </div>
        @endif
        @endif

        {{-- ── FINAL + CAMPEÓN ───────────────────────────── --}}
        @if($final->isNotEmpty())
        <div class="flex flex-col" style="padding-top: {{ $pt['final'] }}px;">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center bg-amber-50 border border-amber-200 text-amber-800 rounded-lg py-1 mb-4 shadow-xs">Final</p>
            @foreach($final as $match)
                @include('admin.tournaments.partials.bracket-match', ['match' => $match, 'color' => 'yellow'])

                @if($match->status === 'finished')
                @php
                    $hasPenalties = !is_null($match->home_penalties);
                    $champion = $hasPenalties
                        ? ($match->home_penalties > $match->away_penalties ? $match->homeTeam : $match->awayTeam)
                        : ($match->home_score > $match->away_score ? $match->homeTeam : $match->awayTeam);
                @endphp
                
                {{-- Bloque de Coronación Integrado --}}
                <div class="flex flex-col items-center bg-gradient-to-b from-amber-50 to-white border border-amber-200 rounded-2xl p-4 mt-6 text-center shadow-md max-w-[200px] mx-auto transition-all hover:scale-105">
                    <div class="relative inline-block mb-2">
                        @if($champion->shield_url)
                            <img src="{{ Storage::url($champion->shield_url) }}"
                                 class="w-14 h-14 rounded-full object-cover border-2 border-amber-400 shadow-sm p-0.5 bg-white">
                        @else
                            <div class="w-14 h-14 rounded-full bg-amber-100 border-2 border-amber-400 flex items-center justify-center text-amber-700 font-black text-lg shadow-inner">
                                {{ strtoupper(substr($champion->name, 0, 2)) }}
                            </div>
                        @endif
                        <span class="absolute -top-1.5 -right-1.5 bg-yellow-400 text-white rounded-full p-0.5 border border-white text-xs shadow shadow-black/10 animate-bounce">🏆</span>
                    </div>
                    <p class="font-black text-amber-800 tracking-tight text-xs leading-tight truncate w-full">{{ $champion->name }}</p>
                    
                    {{-- Texto de penales agregado si existieran --}}
                    @if($hasPenalties)
                        <p class="text-[10px] font-mono text-blue-600 font-bold mt-1">
                            ({{ $match->home_penalties }}-{{ $match->away_penalties }} pen)
                        </p>
                    @endif
                    
                    <p class="text-[10px] font-bold uppercase tracking-wider text-amber-500 mt-1">Campeón</p>
                </div>
                @endif
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection