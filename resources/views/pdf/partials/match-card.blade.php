@php
    $homeWins = $match->status === 'finished' && (
        !is_null($match->home_penalties)
            ? $match->home_penalties > $match->away_penalties
            : $match->home_score > $match->away_score
    );
    $awayWins = $match->status === 'finished' && !$homeWins;
@endphp
<div class="{{ $isFinal ?? false ? 'final-card' : 'match-card' }}">
    <div class="match-row {{ $homeWins ? 'winner' : '' }}">
        <span class="team-name">{{ $match->homeTeam->name }}</span>
        <span class="score">{{ $match->status === 'finished' ? $match->home_score : '?' }}</span>
    </div>
    <div class="match-row {{ $awayWins ? 'winner' : '' }}">
        <span class="team-name">{{ $match->awayTeam->name }}</span>
        <span class="score">{{ $match->status === 'finished' ? $match->away_score : '?' }}</span>
    </div>
    @if(!is_null($match->home_penalties))
        <div class="match-footer pen">Pen: {{ $match->home_penalties }}-{{ $match->away_penalties }}</div>
    @else
        <div class="match-footer">
            {{ $match->status === 'finished' ? 'Finalizado' : $match->played_at->format('d/m H:i') }}
        </div>
    @endif
</div>