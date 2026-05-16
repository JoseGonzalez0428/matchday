<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: Arial, sans-serif; font-size: 9px; color: #1C1C1C; }

            .header { border-bottom: 3px solid #1A6B3A; padding-bottom: 6px; margin-bottom: 12px; }
            .title  { font-size: 15px; font-weight: bold; color: #1A6B3A; }
            .sub    { font-size: 8px; color: #666; margin-top: 2px; }

            .section-title { font-size: 8px; font-weight: bold; color: white;
                            background: #1A6B3A; padding: 3px 6px; margin-bottom: 4px;
                            margin-top: 10px; }

            .col2 { width: 49%; float: left; }
            .col2r { width: 49%; float: right; }
            .col4 { width: 24%; float: left; }
            .clearfix { clear: both; }

            .card { border: 1px solid #ccc; margin-bottom: 4px; font-size: 8px; }
            .card-final { border: 2px solid #F59E0B; margin-bottom: 4px; font-size: 8px; }

            .row { padding: 3px 5px; border-bottom: 1px solid #eee; }
            .row-w { padding: 3px 5px; border-bottom: 1px solid #eee;
                    background: #D6EFD8; font-weight: bold; color: #1A6B3A; }
            .row-wy { padding: 3px 5px; border-bottom: 1px solid #eee;
                    background: #FEF3C7; font-weight: bold; color: #B45309; }

            .team { display: inline-block; width: 78%; }
            .score { display: inline-block; width: 20%; text-align: right; font-weight: bold; }

            .foot { background: #f5f5f5; padding: 2px 5px; font-size: 7px;
                    color: #888; text-align: center; }
            .foot-pen { background: #EFF6FF; padding: 2px 5px; font-size: 7px;
                        color: #2563EB; text-align: center; }

            .champion { text-align: center; border: 2px solid #F59E0B;
                        background: #FFFBEB; padding: 6px; margin-top: 4px; }
            .champ-label { font-size: 7px; color: #92400E; text-transform: uppercase; }
            .champ-name  { font-size: 11px; font-weight: bold; color: #B45309; }

            .footer { position: fixed; bottom: 0; width: 100%; border-top: 1px solid #ccc;
                    font-size: 7px; color: #999; text-align: center; padding-top: 3px; }

            .col4 { width: 24%; float: left; page-break-inside: avoid; }
            .card { border: 1px solid #ccc; margin-bottom: 4px; font-size: 8px; page-break-inside: avoid; }
            .card-final { border: 2px solid #F59E0B; margin-bottom: 4px; font-size: 8px; page-break-inside: avoid; }
        </style>
    </head>
    <body>

        <div class="header">
            <div class="title">MatchDay &mdash; Bracket del Torneo</div>
            <div class="sub">{{ $tournament->name }} &middot; {{ $tournament->edition }} &middot; Generado el {{ now()->format('d/m/Y H:i') }}</div>
        </div>

        @php
        function pdfCard($match, $final = false) {
            $hw = $match->status === 'finished' && (
                !is_null($match->home_penalties)
                    ? $match->home_penalties > $match->away_penalties
                    : $match->home_score > $match->away_score
            );
            $aw = $match->status === 'finished' && !$hw;
            $cardClass = $final ? 'card-final' : 'card';
            $winClass  = $final ? 'row-wy' : 'row-w';

            $hs = $match->status === 'finished' ? $match->home_score : '?';
            $as = $match->status === 'finished' ? $match->away_score : '?';

            $out  = "<div class='{$cardClass}'>";
            $out .= "<div class='".($hw ? $winClass : 'row')."'>";
            $out .= "<span class='team'>".e($match->homeTeam->name)."</span>";
            $out .= "<span class='score'>{$hs}</span></div>";

            $out .= "<div class='".($aw ? $winClass : 'row')."'>";
            $out .= "<span class='team'>".e($match->awayTeam->name)."</span>";
            $out .= "<span class='score'>{$as}</span></div>";

            if (!is_null($match->home_penalties)) {
                $out .= "<div class='foot-pen'>Pen: {$match->home_penalties}-{$match->away_penalties}</div>";
            } else {
                $status = $match->status === 'finished' ? 'Finalizado' : $match->played_at->format('d/m H:i');
                $out .= "<div class='foot'>{$status}</div>";
            }
            $out .= "</div>";
            return $out;
        }
        @endphp

        {{-- RONDA DE 32 --}}
        @if(isset($matches['round32']))
        <div style="clear:both; margin-top:8px;">
            <div class="section-title">Ronda de 32</div>
        </div>
        <div class="col2">
            @foreach($matches['round32']->take(8) as $m)
                {!! pdfCard($m) !!}
            @endforeach
        </div>
        <div class="col2r">
            @foreach($matches['round32']->skip(8) as $m)
                {!! pdfCard($m) !!}
            @endforeach
        </div>
        <div class="clearfix"></div>
        @endif

        {{-- OCTAVOS --}}
        @if(isset($matches['round16']))
        <div style="clear:both; margin-top:10px;">
            <div class="section-title">Octavos de final</div>
        </div>
        <div class="col2">
            @foreach($matches['round16']->take(4) as $m)
                {!! pdfCard($m) !!}
            @endforeach
        </div>
        <div class="col2r">
            @foreach($matches['round16']->skip(4) as $m)
                {!! pdfCard($m) !!}
            @endforeach
        </div>
        <div class="clearfix"></div>
        @endif

        {{-- CUARTOS --}}
        @if(isset($matches['quarter']))
        <div style="clear:both; margin-top:10px; {{ isset($matches['round32']) || isset($matches['round16']) ? 'page-break-before: always;' : '' }}">
            <div class="section-title">Cuartos de final</div>
        </div>
        @foreach($matches['quarter']->chunk(2) as $chunk)
        <div class="col4">
            @foreach($chunk as $m)
                {!! pdfCard($m) !!}
            @endforeach
        </div>
        @endforeach
        <div class="clearfix"></div>
        @endif

        {{-- SEMIS + FINAL --}}
        @if(isset($matches['semi']) || isset($matches['final']))
        <div style="clear:both; margin-top:10px; display:block; width:100%;">
            <div class="col2">
                @if(isset($matches['semi']))
                <div class="section-title">Semifinales</div>
                @foreach($matches['semi'] as $m)
                    {!! pdfCard($m) !!}
                @endforeach
                @endif
            </div>
            <div class="col2r">
                @if(isset($matches['final']))
                <div class="section-title">Final</div>
                @foreach($matches['final'] as $m)
                    {!! pdfCard($m, true) !!}
                    @if($m->status === 'finished')
                    @php
                        $champ = !is_null($m->home_penalties)
                            ? ($m->home_penalties > $m->away_penalties ? $m->homeTeam : $m->awayTeam)
                            : ($m->home_score > $m->away_score ? $m->homeTeam : $m->awayTeam);
                    @endphp
                    <div class="champion">
                        <div class="champ-label">Campeon</div>
                        <div class="champ-name">{{ $champ->name }}</div>
                    </div>
                    @endif
                @endforeach
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
        @endif

        <div class="footer">MatchDay &middot; Sistema de Gestion de Torneos &middot; {{ $tournament->name }}</div>
    </body>
</html>