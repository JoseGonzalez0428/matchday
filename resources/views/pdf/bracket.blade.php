<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            /* Reset y Estilos Base Compatibles con PDF */
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #334155; line-height: 1.4; padding: 30px 40px; }

            /* Encabezado Principal */
            .header { border-bottom: 2px solid #16a34a; padding-bottom: 8px; margin-bottom: 16px; }
            .title  { font-size: 18px; font-weight: bold; color: #14532d; tracking-tight: -0.5px; }
            .sub    { font-size: 9px; color: #94a3b8; margin-top: 3px; font-weight: 500; }

            /* Títulos de las Fases del Bracket */
            .section-title { font-size: 9px; font-weight: bold; color: #14532d; text-transform: uppercase;
                            background: #f0fdf4; padding: 4px 8px; margin-bottom: 6px;
                            margin-top: 14px; border-left: 3px solid #16a34a; tracking-wide: 0.5px; }

            /* Estructura de Columnas (Floats Estables) */
            .col2 { width: 48.5%; float: left; }
            .col2r { width: 48.5%; float: right; }
            .col4 { width: 23.5%; float: left; margin-right: 2%; }
            .col4:last-child { margin-right: 0; }
            .clearfix { clear: both; height: 0; overflow: hidden; }

            /* Tarjetas de Partido */
            .card { border: 1px solid #e2e8f0; margin-bottom: 6px; border-radius: 6px; overflow: hidden; background: #ffffff; page-break-inside: avoid; }
            .card-final { border: 2px solid #f59e0b; margin-bottom: 6px; border-radius: 6px; overflow: hidden; background: #ffffff; page-break-inside: avoid; }

            /* Filas Internas de Equipos */
            .row { padding: 4px 8px; border-bottom: 1px solid #f1f5f9; background: #ffffff; font-size: 9px; color: #475569; }
            .row-w { padding: 4px 8px; border-bottom: 1px solid #f1f5f9; background: #f0fdf4; font-weight: bold; color: #166534; font-size: 9px; }
            .row-w shadow { background: #f0fdf4; }
            .row-wy { padding: 4px 8px; border-bottom: 1px solid #f1f5f9; background: #fffbeb; font-weight: bold; color: #b45309; font-size: 9px; }

            .team { display: inline-block; width: 75%; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; vertical-align: middle; }
            .score { display: inline-block; width: 22%; text-align: right; font-weight: bold; font-family: monospace; font-size: 10px; vertical-align: middle; }

            /* Pies de Tarjeta (Estados) */
            .foot { background: #f8fafc; padding: 3px 8px; font-size: 8px; color: #94a3b8; text-align: center; font-weight: 500; }
            .foot-pen { background: #eff6ff; padding: 3px 8px; font-size: 8px; color: #2563eb; text-align: center; font-weight: bold; border-top: 1px solid #dbeafe; }

            /* Recuadro Destacado del Campeón */
            .champion { text-align: center; border: 2px solid #f59e0b; border-radius: 8px;
                        background: #fffbeb; padding: 8px; margin-top: 8px; page-break-inside: avoid; }
            .champ-label { font-size: 8px; color: #d97706; text-transform: uppercase; font-weight: bold; tracking-wide: 0.5px; }
            .champ-name  { font-size: 12px; font-weight: bold; color: #78350f; margin-top: 2px; }

            /* Pie de Página Fijo */
            .footer { position: fixed; bottom: -10px; width: 100%; border-top: 1px solid #e2e8f0;
                    font-size: 8px; color: #94a3b8; text-align: center; padding-top: 5px; font-weight: 500; }
        </style>
    </head>
    <body>

        {{-- Encabezado --}}
        <div class="header">
            <div class="title">MatchDay &mdash; Bracket del Torneo</div>
            <div class="sub">{{ $tournament->name }} &middot; Edición {{ $tournament->edition }} &middot; Generado el {{ now()->format('d/m/Y H:i') }} hs</div>
        </div>

        {{-- Helper PHP para Renderizar las Tarjetas --}}
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
            $out .= "<span class='team'>".e($match->homeTeam?->name ?? '(Equipo eliminado)')."</span>";
            $out .= "<span class='score'>{$hs}</span></div>";

            $out .= "<div class='".($aw ? $winClass : 'row')."'>";
            $out .= "<span class='team'>".e($match->awayTeam?->name ?? '(Equipo eliminado)')."</span>";
            $out .= "<span class='score'>{$as}</span></div>";

            if (!is_null($match->home_penalties)) {
                $out .= "<div class='foot-pen'>Penales: {$match->home_penalties}-{$match->away_penalties}</div>";
            } else {
                $status = $match->status === 'finished' ? 'Finalizado' : $match->played_at->format('d/m H:i').' hs';
                $out .= "<div class='foot'>{$status}</div>";
            }
            $out .= "</div>";
            return $out;
        }
        @endphp

        {{-- RONDA DE 32 --}}
        @if(isset($matches['round32']) && $matches['round32']->isNotEmpty())
        <div style="clear:both; margin-top:4px;">
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

        {{-- OCTAVOS DE FINAL --}}
        @if(isset($matches['round16']) && $matches['round16']->isNotEmpty())
        <div style="clear:both; margin-top:4px;">
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

        {{-- CUARTOS DE FINAL --}}
        @if(isset($matches['quarter']) && $matches['quarter']->isNotEmpty())
        <div style="clear:both; margin-top:4px; {{ isset($matches['round32']) || isset($matches['round16']) ? 'page-break-before: always;' : '' }}">
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

        {{-- SEMIFINALES Y FINAL --}}
        @if((isset($matches['semi']) && $matches['semi']->isNotEmpty()) || (isset($matches['final']) && $matches['final']->isNotEmpty()))
        <div style="clear:both; margin-top:4px; display:block; width:100%;">
            <div class="col2">
                @if(isset($matches['semi']) && $matches['semi']->isNotEmpty())
                <div class="section-title">Semifinales</div>
                @foreach($matches['semi'] as $m)
                    {!! pdfCard($m) !!}
                @endforeach
                @endif
            </div>
            <div class="col2r">
                @if(isset($matches['final']) && $matches['final']->isNotEmpty())
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
                        <div class="champ-name">{{ $champ?->name ?? '(Equipo eliminado)' }}</div>
                    </div>
                    @endif
                @endforeach
                @endif
            </div>
            <div class="clearfix"></div>
        </div>
        @endif

        {{-- Footer Global --}}
        <div class="footer">MatchDay &middot; Sistema de Gestion de Torneos &middot; {{ $tournament->name }}</div>
    </body>
</html>