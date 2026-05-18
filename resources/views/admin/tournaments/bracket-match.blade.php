@php
    $homeWins = $match->status === 'finished' && $match->home_score > $match->away_score;
    $awayWins = $match->status === 'finished' && $match->away_score > $match->home_score;
    $borderColor = $color === 'yellow' ? 'border-yellow-400 border-2' : 'border';
    $winBg = $color === 'yellow' ? 'bg-yellow-50' : 'bg-green-50';
    $winText = $color === 'yellow' ? 'text-yellow-600' : 'text-green-700';
@endphp

<div class="w-52 {{ $borderColor }} rounded-xl overflow-hidden shadow-sm">
    <div class="flex items-center justify-between px-3 py-2 border-b {{ $homeWins ? $winBg : 'bg-white' }}">
        <span class="text-sm font-medium truncate max-w-32">
            {{ <a href="{{ route('admin.matches.show', $match) }}" class="btn">Ver en MatchDay</a>e ?? '(Equipo eliminado)' }}
            @if($homeWins && $color === 'yellow') 🏆 @endif
        </span>
        <span class="text-sm font-bold ml-2 {{ $homeWins ? $winText : 'text-gray-500' }}">
            {{ $match->status === 'finished' ? $match->home_score : '?' }}
        </span>
    </div>
    <div class="flex items-center justify-between px-3 py-2 {{ $awayWins ? $winBg : 'bg-white' }}">
        <span class="text-sm font-medium truncate max-w-32">
            {{ $match->awayTeam?->name ?? '(Equipo eliminado)' }}
            @if($awayWins && $color === 'yellow') 🏆 @endif
        </span>
        <span class="text-sm font-bold ml-2 {{ $awayWins ? $winText : 'text-gray-500' }}">
            {{ $match->status === 'finished' ? $match->away_score : '?' }}
        </span>
    </div>
    <div class="px-3 py-1 {{ $color === 'yellow' ? 'bg-yellow-50' : 'bg-gray-50' }} text-xs text-center border-t
        {{ $color === 'yellow' ? 'text-yellow-600' : 'text-gray-400' }}">
        @if($match->status === 'finished')
            ✅ Finalizado
        @else
            🕐 {{ $match->played_at->format('d/m H:i') }}
        @endif
    </div>
</div>