@php
    $isFinished = $match->status === 'finished';
    $hasPenalties = !is_null($match->home_penalties);
    
    // Corregimos la lógica para que tome en cuenta los penales en eliminatorias
    $homeWins = $isFinished && ($hasPenalties ? $match->home_penalties > $match->away_penalties : $match->home_score > $match->away_score);
    $awayWins = $isFinished && !$homeWins && ($hasPenalties ? $match->away_penalties > $match->home_penalties : $match->away_score > $match->home_score);
    
    $borderColor = $color === 'yellow' ? 'border-amber-400 ring-2 ring-amber-100' : 'border-gray-200';
    $winBg = $color === 'yellow' ? 'bg-amber-50/70' : 'bg-emerald-50/50';
    $winText = $color === 'yellow' ? 'text-amber-700' : 'text-emerald-700';
@endphp

{{-- Tarjeta de Partido Individual en el Bracket --}}
<div class="w-52 bg-white {{ $borderColor }} rounded-xl overflow-hidden shadow-sm transition-all hover:shadow-md border">
    
    {{-- Equipo Local --}}
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-100 transition-colors {{ $homeWins ? $winBg : 'bg-white' }}">
        <span class="text-xs font-bold truncate max-w-[130px] flex items-center gap-1.5 {{ $homeWins ? 'text-gray-900' : 'text-gray-600 font-medium' }}">
            {{ $match->homeTeam?->name ?? '(Equipo eliminado)' }}
        </span>
        <div class="flex items-center gap-1.5">
            {{-- Indicador de penales locales si aplican --}}
            @if($hasPenalties)
                <span class="text-[9px] font-mono text-blue-600 font-bold">({{ $match->home_penalties }})</span>
            @endif
            <span class="text-xs font-black tracking-tight {{ $homeWins ? $winText : 'text-gray-400' }}">
                {{ $isFinished ? $match->home_score : '?' }}
            </span>
        </div>
    </div>

    {{-- Equipo Visitante --}}
    <div class="flex items-center justify-between px-3 py-2 transition-colors {{ $awayWins ? $winBg : 'bg-white' }}">
        <span class="text-xs font-bold truncate max-w-[130px] flex items-center gap-1.5 {{ $awayWins ? 'text-gray-900' : 'text-gray-600 font-medium' }}">
            {{ $match->awayTeam?->name ?? '(Equipo eliminado)' }}
        </span>
        <div class="flex items-center gap-1.5">
            {{-- Indicador de penales visitantes si aplican --}}
            @if($hasPenalties)
                <span class="text-[9px] font-mono text-blue-600 font-bold">({{ $match->away_penalties }})</span>
            @endif
            <span class="text-xs font-black tracking-tight {{ $awayWins ? $winText : 'text-gray-400' }}">
                {{ $isFinished ? $match->away_score : '?' }}
            </span>
        </div>
    </div>

    {{-- Pie de Tarjeta con el Estado del Partido --}}
    <div class="px-3 py-1 text-[10px] font-bold tracking-wider uppercase text-center border-t border-gray-100 font-mono
        {{ $color === 'yellow' ? 'bg-amber-50/40 text-amber-600' : 'bg-slate-50 text-gray-400' }}">
        @if($isFinished)
            <span class="flex items-center justify-center gap-1 text-emerald-700">
                <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Finalizado
            </span>
        @else
            <span class="flex items-center justify-center gap-1 text-gray-400">
                🕐 {{ $match->played_at->format('d/m H:i') }}
            </span>
        @endif
    </div>
</div>