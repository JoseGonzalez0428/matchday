<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipo Registrado — MatchDay</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background: #15803d; padding: 32px; text-align: center; }
        .header-logo { font-size: 48px; margin-bottom: 8px; }
        .header-title { color: white; font-size: 24px; font-weight: bold; margin: 0; }
        .header-sub { color: #bbf7d0; font-size: 14px; margin-top: 4px; }
        .content { padding: 32px; }
        .team-box { background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 12px; padding: 24px; margin: 24px 0; text-align: center; }
        .team-avatar { width: 64px; height: 64px; background: #15803d; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; color: white; font-size: 24px; font-weight: bold; margin-bottom: 12px; }
        .team-name { font-size: 24px; font-weight: bold; color: #1f2937; }
        .team-country { font-size: 14px; color: #6b7280; margin-top: 4px; }
        .info-row { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .info-label { color: #6b7280; }
        .info-value { font-weight: bold; color: #1f2937; }
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
            <p class="header-sub">Equipo Registrado</p>
        </div>

        {{-- Contenido --}}
        <div class="content">
            <p style="color: #374151; font-size: 16px;">
                ¡Bienvenido a MatchDay! Tu equipo ha sido registrado exitosamente.
            </p>

            {{-- Info del equipo --}}
            <div class="team-box">
                <div class="team-avatar">
                    {{ strtoupper(substr($team->name, 0, 2)) }}
                </div>
                <div class="team-name">{{ $team->name }}</div>
                @if($team->country)
                    <div class="team-country">{{ $team->country }}</div>
                @endif
            </div>

            {{-- Detalles --}}
            <div>
                <div class="info-row">
                    <span class="info-label">Capitán</span>
                    <span class="info-value">{{ $team->captain->name ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jugadores registrados</span>
                    <span class="info-value">{{ $team->players()->count() }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Fecha de registro</span>
                    <span class="info-value">{{ $team->created_at->format('d/m/Y') }}</span>
                </div>
            </div>

            <p style="color: #6b7280; font-size: 14px; margin-top: 16px;">
                Puedes acceder al sistema para ver los partidos y estadísticas de tu equipo.
            </p>

            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">Ir a MatchDay</a>
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