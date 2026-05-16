<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body        { font-family: Arial, sans-serif; font-size: 11px; color: #1C1C1C; }
        .header     { border-bottom: 3px solid #1A6B3A; padding-bottom: 8px; margin-bottom: 16px; }
        .title      { font-size: 18px; font-weight: bold; color: #1A6B3A; }
        .subtitle   { font-size: 10px; color: #666; }
        .bracket    { display: table; width: 100%; }
        .stage-col  { display: table-cell; vertical-align: middle; padding: 0 10px; }
        .stage-title { font-size: 10px; font-weight: bold; color: #888; text-transform: uppercase;
                       text-align: center; margin-bottom: 8px; letter-spacing: 1px; }
        .match-card { border: 1px solid #ddd; border-radius: 6px; margin-bottom: 12px;
                      overflow: hidden; width: 160px; margin-left: auto; margin-right: auto; }
        .match-row  { padding: 5px 8px; font-size: 10px; border-bottom: 1px solid #eee;
                      display: flex; justify-content: space-between; }
        .match-row:last-child { border-bottom: none; }
        .winner     { background: #D6EFD8; font-weight: bold; color: #1A6B3A; }
        .score      { font-weight: bold; min-width: 20px; text-align: right; }
        .match-footer { background: #f9f9f9; padding: 3px 8px; font-size: 9px;
                        color: #888; text-align: center; border-top: 1px solid #eee; }
        .final-card { border: 2px solid #F59E0B; border-radius: 6px; margin-bottom: 12px;
                      overflow: hidden; width: 180px; margin-left: auto; margin-right: auto; }
        .champion-box { text-align: center; padding: 12px; border: 2px solid #F59E0B;
                        border-radius: 6px; width: 120px; margin: auto; background: #FFFBEB; }
        .champion-title { font-size: 9px; color: #92400E; text-transform: uppercase; font-weight: bold; }
        .champion-name  { font-size: 13px; font-weight: bold; color: #B45309; margin-top: 4px; }
        .arrow { text-align: center; color: #ccc; font-size: 16px; vertical-align: middle; }
        .footer { position: fixed; bottom: 0; width: 100%; border-top: 1px solid #ccc;
                  font-size: 9px; color: #999; text-align: center; padding-top: 4px; }
        .pen { font-size: 9px; color: #2563EB; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">MatchDay — Bracket del Torneo</div>
        <div class="subtitle">{{ $tournament->name }} · {{ $tournament->edition }} · Generado el {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <div class="bracket">

        {{-- RONDA DE 32 --}}
        @if(isset($matches['round32']))
        <div class="stage-col" style="width: 18%;">
            <div class="stage-title">Ronda de 32</div>
            @foreach($matches['round32'] as $match)
                @php
                    $homeWins = $match->status === 'finished' && (
                        !is_null($match->home_penalties)
                            ? $match->home_penalties > $match->away_penalties
                            : $match->home_score > $match->away_score
                    );
                    $awayWins = $match->status === 'finished' && !$homeWins;
                @endphp
                <div class="match-card">
                    <div class="match-row {{ $homeWins ? 'winner' : '' }}">
                        <span>{{ $match->homeTeam->name }}</span>
                        <span class="score">{{ $match->status === 'finished' ? $match->home_score : '?' }}</span>
                    </div>
                    <div class="match-row {{ $awayWins ? 'winner' : '' }}">
                        <span>{{ $match->awayTeam->name }}</span>
                        <span class="score">{{ $match->status === 'finished' ? $match->away_score : '?' }}</span>
                    </div>
                    @if(!is_null($match->home_penalties))
                        <div class="match-footer pen">
                            Penales: {{ $match->home_penalties }}-{{ $match->away_penalties }}
                        </div>
                    @else
                        <div class="match-footer">
                            {{ $match->status === 'finished' ? 'Finalizado' : $match->played_at->format('d/m H:i') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="stage-col arrow" style="width: 2%;">---</div>
        @endif

        {{-- CUARTOS --}}
        @if(isset($matches['quarter']))
        <div class="stage-col" style="width: 22%;">
            <div class="stage-title">Cuartos de final</div>
            @foreach($matches['quarter'] as $match)
                @php
                    $homeWins = $match->status === 'finished' && (
                        !is_null($match->home_penalties)
                            ? $match->home_penalties > $match->away_penalties
                            : $match->home_score > $match->away_score
                    );
                    $awayWins = $match->status === 'finished' && !$homeWins;
                @endphp
                <div class="match-card">
                    <div class="match-row {{ $homeWins ? 'winner' : '' }}">
                        <span>{{ $match->homeTeam->name }}</span>
                        <span class="score">{{ $match->status === 'finished' ? $match->home_score : '?' }}</span>
                    </div>
                    <div class="match-row {{ $awayWins ? 'winner' : '' }}">
                        <span>{{ $match->awayTeam->name }}</span>
                        <span class="score">{{ $match->status === 'finished' ? $match->away_score : '?' }}</span>
                    </div>
                    @if(!is_null($match->home_penalties))
                        <div class="match-footer pen">
                            Penales: {{ $match->home_penalties }}-{{ $match->away_penalties }}
                        </div>
                    @else
                        <div class="match-footer">
                            {{ $match->status === 'finished' ? 'Finalizado' : $match->played_at->format('d/m H:i') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="stage-col arrow" style="width: 3%;"> &gt;&gt; </div>
        @endif

        {{-- SEMIS --}}
        @if(isset($matches['semi']))
        <div class="stage-col" style="width: 22%;">
            <div class="stage-title">Semifinales</div>
            @foreach($matches['semi'] as $match)
                @php
                    $homeWins = $match->status === 'finished' && (
                        !is_null($match->home_penalties)
                            ? $match->home_penalties > $match->away_penalties
                            : $match->home_score > $match->away_score
                    );
                    $awayWins = $match->status === 'finished' && !$homeWins;
                @endphp
                <div class="match-card">
                    <div class="match-row {{ $homeWins ? 'winner' : '' }}">
                        <span>{{ $match->homeTeam->name }}</span>
                        <span class="score">{{ $match->status === 'finished' ? $match->home_score : '?' }}</span>
                    </div>
                    <div class="match-row {{ $awayWins ? 'winner' : '' }}">
                        <span>{{ $match->awayTeam->name }}</span>
                        <span class="score">{{ $match->status === 'finished' ? $match->away_score : '?' }}</span>
                    </div>
                    @if(!is_null($match->home_penalties))
                        <div class="match-footer pen">
                            Penales: {{ $match->home_penalties }}-{{ $match->away_penalties }}
                        </div>
                    @else
                        <div class="match-footer">
                            {{ $match->status === 'finished' ? 'Finalizado' : $match->played_at->format('d/m H:i') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="stage-col arrow" style="width: 3%;"> &gt;&gt; </div>
        @endif

        {{-- FINAL --}}
        @if(isset($matches['final']))
        <div class="stage-col" style="width: 22%;">
            <div class="stage-title">Final</div>
            @foreach($matches['final'] as $match)
                @php
                    $homeWins = $match->status === 'finished' && (
                        !is_null($match->home_penalties)
                            ? $match->home_penalties > $match->away_penalties
                            : $match->home_score > $match->away_score
                    );
                    $awayWins = $match->status === 'finished' && !$homeWins;
                @endphp
                <div class="final-card">
                    <div class="match-row {{ $homeWins ? 'winner' : '' }}">
                        <span>{{ $match->homeTeam->name }}</span>
                        <span class="score">{{ $match->status === 'finished' ? $match->home_score : '?' }}</span>
                    </div>
                    <div class="match-row {{ $awayWins ? 'winner' : '' }}">
                        <span>{{ $match->awayTeam->name }}</span>
                        <span class="score">{{ $match->status === 'finished' ? $match->away_score : '?' }}</span>
                    </div>
                    @if(!is_null($match->home_penalties))
                        <div class="match-footer pen">
                            Penales: {{ $match->home_penalties }}-{{ $match->away_penalties }}
                        </div>
                    @else
                        <div class="match-footer">
                            {{ $match->status === 'finished' ? 'Finalizado' : $match->played_at->format('d/m H:i') }}
                        </div>
                    @endif
                </div>

                {{-- Campeón --}}
                @if($match->status === 'finished')
                @php
                    $champion = !is_null($match->home_penalties)
                        ? ($match->home_penalties > $match->away_penalties ? $match->homeTeam : $match->awayTeam)
                        : ($match->home_score > $match->away_score ? $match->homeTeam : $match->awayTeam);
                @endphp
                @endif
            @endforeach
        </div>

        {{-- Campeón box --}}
        @if(isset($champion))
        <div class="stage-col arrow" style="width: 3%;"> &gt;&gt; </div>
        <div class="stage-col" style="width: 22%;">
            <div class="stage-title">Campeón</div>
            <div class="champion-box">
                <div class="champion-name">{{ $champion->name }}</div>
            </div>
        </div>
        @endif
        @endif

    </div>

    <div class="footer">MatchDay · Sistema de Gestión de Torneos · {{ $tournament->name }}</div>
</body>
</html>