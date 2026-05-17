<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Campeones! — MatchDay</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #15803d; padding: 32px; text-align: center; }
        .header-logo { font-size: 48px; margin-bottom: 8px; }
        .header-title { color: white; font-size: 24px; font-weight: bold; margin: 0; }
        .header-sub { color: #bbf7d0; font-size: 14px; margin-top: 4px; }
        .content { padding: 32px 40px; color: #374151; font-size: 15px; line-height: 1.8; }
        .champion-box { background: #fef9c3; border: 2px solid #fbbf24; border-radius: 16px; padding: 32px; text-align: center; margin: 24px 0; }
        .trophy { font-size: 64px; margin-bottom: 8px; }
        .champion-label { font-size: 12px; color: #92400e; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }
        .champion-name { font-size: 32px; font-weight: bold; color: #b45309; margin: 8px 0; }
        .tournament-name { font-size: 16px; color: #92400e; }
        .score-box { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 20px; text-align: center; margin: 16px 0; }
        .score-teams { font-size: 14px; color: #374151; margin-bottom: 8px; }
        .score { font-size: 36px; font-weight: bold; color: #15803d; }
        .penalties { font-size: 13px; color: #2563eb; margin-top: 4px; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .info-label { color: #6b7280; }
        .info-value { font-weight: bold; color: #1f2937; }
        .btn { display: inline-block; background: #15803d; color: white; padding: 14px 32px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 15px; margin-top: 16px; }
        .footer { background: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer-text { font-size: 12px; color: #9ca3af; margin: 2px 0; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <div class="header-logo">⚽</div>
            <p class="header-title">MatchDay</p>
            <p class="header-sub">Sistema de Gestión de Torneos</p>
        </div>

        {{-- Contenido --}}
        <div class="content">
            <p>¡Felicitaciones, <strong>{{ $team->captain->name ?? 'Capitán' }}</strong>!</p>

            {{-- Box campeón --}}
            <div class="champion-box">
                <div class="trophy">🏆</div>
                <div class="champion-label">Campeón</div>
                <div class="champion-name">{{ $team->name }}</div>
                <div class="tournament-name">{{ $tournament->name }} {{ $tournament->edition }}</div>
            </div>

            {{-- Resultado de la final --}}
            <div class="score-box">
                <div class="score-teams">
                    {{ $final->homeTeam->name }} vs {{ $final->awayTeam->name }}
                </div>
                <div class="score">
                    {{ $final->home_score }} — {{ $final->away_score }}
                </div>
                @if(!is_null($final->home_penalties))
                    <div class="penalties">
                        Penales: {{ $final->home_penalties }} — {{ $final->away_penalties }}
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div>
                <div class="info-row">
                    <span class="info-label">Torneo</span>
                    <span class="info-value">{{ $tournament->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Edición</span>
                    <span class="info-value">{{ $tournament->edition }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de la final</span>
                    <span class="info-value">{{ $final->played_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <p style="margin-top: 24px;">
                Tu equipo ha demostrado ser el mejor del torneo. ¡Enhorabuena por este logro!
            </p>

            <div style="text-align: center;">
                <a href="{{ config('app.url') }}/admin/tournaments/{{ $tournament->id }}" class="btn">
                    Ver torneo en MatchDay
                </a>
            </div>
        </div>

        {{-- Footer --}}
        <div class="footer">
            <p class="footer-text">MatchDay · Sistema de Gestión de Torneos</p>
            <p class="footer-text">Copa MatchDay 2026 · Desarrollo Web Avanzado · UASLP</p>
        </div>
    </div>
</body>
</html>