<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Partido — MatchDay</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #15803d; padding: 32px; text-align: center; }
        .header-logo { font-size: 48px; margin-bottom: 8px; }
        .header-title { color: white; font-size: 24px; font-weight: bold; margin: 0; }
        .header-sub { color: #bbf7d0; font-size: 14px; margin-top: 4px; }
        .content { padding: 32px; }
        .match-box { background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 12px; padding: 24px; margin: 24px 0; text-align: center; }
        .teams { display: flex; justify-content: space-between; align-items: center; gap: 16px; }
        .team { flex: 1; }
        .team-name { font-size: 18px; font-weight: bold; color: #1f2937; }
        .team-role { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .score { font-size: 48px; font-weight: bold; color: #15803d; padding: 0 16px; }
        .penalties { font-size: 13px; color: #2563eb; margin-top: 8px; font-weight: bold; }
        .stage-badge { display: inline-block; background: #15803d; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; margin-bottom: 16px; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .info-label { color: #6b7280; }
        .info-value { font-weight: bold; color: #1f2937; }
        .winner-box { background: #fef9c3; border: 2px solid #fbbf24; border-radius: 12px; padding: 16px; text-align: center; margin: 16px 0; }
        .winner-label { font-size: 12px; color: #92400e; text-transform: uppercase; font-weight: bold; }
        .winner-name { font-size: 20px; font-weight: bold; color: #b45309; margin-top: 4px; }
        .footer { background: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer-text { font-size: 12px; color: #9ca3af; }
        .btn { display: inline-block; background: #15803d; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 14px; margin-top: 16px; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="header-logo">⚽</div>
            <p class="header-title">MatchDay</p>
            <p class="header-sub">Resultado del Partido</p>
        </div>

        {{-- Contenido --}}
        <div class="content">
            <p style="color: #374151; font-size: 16px;">Hola, aquí tienes el resultado del partido:</p>

            {{-- Badge de fase --}}
            <div style="text-align: center;">
                <span class="stage-badge">
                    {{ \App\Helpers\StatusHelper::stage($match->stage) }}
                </span>
            </div>

            {{-- Marcador --}}
            <div class="match-box">
                <div class="teams">
                    <div class="team">
                        <div class="team-name">{{ $match->homeTeam->name }}</div>
                        <div class="team-role">Local</div>
                    </div>
                    <div class="score">{{ $match->home_score }} — {{ $match->away_score }}</div>
                    <div class="team">
                        <div class="team-name">{{ $match->awayTeam->name }}</div>
                        <div class="team-role">Visitante</div>
                    </div>
                </div>
                @if(!is_null($match->home_penalties))
                    <div class="penalties">
                        Penales: {{ $match->home_penalties }} — {{ $match->away_penalties }}
                    </div>
                @endif
            </div>

            {{-- Info del partido --}}
            <div>
                <div class="info-row">
                    <span class="info-label">Torneo</span>
                    <span class="info-value">{{ $match->tournament->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha</span>
                    <span class="info-value">{{ $match->played_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fase</span>
                    <span class="info-value">{{ \App\Helpers\StatusHelper::stage($match->stage) }}</span>
                </div>
            </div>

            {{-- Ganador en eliminatorias --}}
            @if(in_array($match->stage, ['round32', 'round16', 'quarter', 'semi', 'final']))
                @php
                    $winner = !is_null($match->home_penalties)
                        ? ($match->home_penalties > $match->away_penalties ? $match->homeTeam->name : $match->awayTeam->name)
                        : ($match->home_score > $match->away_score ? $match->homeTeam->name : $match->awayTeam->name);
                @endphp
                <div class="winner-box">
                    <div class="winner-label">🏆 Avanza a la siguiente fase</div>
                    <div class="winner-name">{{ $winner }}</div>
                </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/admin/matches/{{ $match->id }}" class="btn">Ver en MatchDay</a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p class="footer-text">MatchDay · Sistema de Gestión de Torneos</p>
            <p class="footer-text">Copa MatchDay 2026 · Aplicaciones Web Interactivas · UASLP</p>
        </div>
    </div>
</body>
</html>