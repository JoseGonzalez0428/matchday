@php
    $isFinished = $match->status === 'finished';
    $hasPenalties = !is_null($match->home_penalties);
    
    // Ajuste lógico estricto para evitar falsos ganadores en empates regulares
    $homeWins = $isFinished && ($hasPenalties ? $match->home_penalties > $match->away_penalties : $match->home_score > $match->away_score);
    $awayWins = $isFinished && ($hasPenalties ? $match->away_penalties > $match->home_penalties : $match->away_score > $match->home_score);
    
    // Configuración dinámica de bordes y fondos según el contexto de la fase
    $isFinalCard = $isFinal ?? false;
    $cardBorder = $isFinalCard ? 'border-amber-400 ring-2 ring-amber-100 shadow-md' : 'border-gray-100 shadow-sm';
    $winnerBg = $isFinalCard ? 'bg-amber-50/60' : 'bg-emerald-50/50';
    $winnerText = $isFinalCard ? 'text-amber-700' : 'text-emerald-700';
@endphp

{{-- Tarjeta de Partido del Bracket --}}
<div class="w-52 bg-white border {{ $cardBorder }} rounded-xl overflow-hidden transition-all hover:shadow-md">
    
    {{-- Fila Equipo Local --}}
    <div class="flex items-center justify-between px-3 py-2 border-b border-gray-50 transition-colors {{ $homeWins ? $winnerBg : 'bg-white' }}">
        <span class="text-xs font-bold truncate max-w-[130px] {{ $homeWins ? 'text-gray-900' : 'text-gray-600 font-medium' }}">
            {{ $match->homeTeam?->name ?? '(Equipo eliminado)' }}
        </span>
        <div class="flex items-center gap-1">
            @if($hasPenalties)
                <span class="text-[9px] font-mono text-blue-500 font-bold">({{ $match->home_penalties }})</span>
            @endif
            <span class="text-xs font-black tracking-tight font-mono {{ $homeWins ? $winnerText : 'text-gray-400' }}">
                {{ $isFinished ? $match->home_score : '?' }}
            </span>
        </div>
    </div>

    {{-- Fila Equipo Visitante --}}
    <div class="flex items-center justify-between px-3 py-2 transition-colors {{ $awayWins ? $winnerBg : 'bg-white' }}">
        <span class="text-xs font-bold truncate max-w-[130px] {{ $awayWins ? 'text-gray-900' : 'text-gray-600 font-medium' }}">
            {{ $match->awayTeam?->name ?? '(Equipo eliminado)' }}
        </span>
        <div class="flex items-center gap-1">
            @if($hasPenalties)
                <span class="text-[9px] font-mono text-blue-500 font-bold">({{ $match->away_penalties }})</span>
            @endif
            <span class="text-xs font-black tracking-tight font-mono {{ $awayWins ? $winnerText : 'text-gray-400' }}">
                {{ $isFinished ? $match->away_score : '?' }}
            </span>
        </div>
    </div>

    {{-- Pie de la Tarjeta (Estado / Horario) --}}
    <div class="px-3 py-1 text-[9px] font-bold tracking-wider uppercase text-center border-t border-gray-50 font-mono
        {{ $isFinalCard ? 'bg-amber-50/30 text-amber-600' : 'bg-slate-50 text-gray-400' }}">
        @if($hasPenalties)
            <span class="text-blue-600 font-black">Penales: {{ $match->home_penalties }}-{{ $match->away_penalties }}</span>
        @else
            @if($isFinished)
                <span class="flex items-center justify-center gap-1 text-emerald-700">
                    <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Finalizado
                </span>
            @else
                <span class="flex items-center justify-center gap-1">
                    🕐 {{ $match->played_at->format('d/m H:i') }}
                </span>
            @endif
        @endif
    </div>
</div>