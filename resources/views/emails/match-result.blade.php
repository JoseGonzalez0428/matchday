<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Partido — MatchDay</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
        table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        td { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
    </style>
</head>
<body style="background-color: #f8fafc; margin: 0; padding: 40px 0;">

    {{-- TABLA MAESTRA: Esta tabla fuerza el ancho de 600px centrado y simula los márgenes externos --}}
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05); border: 1px solid #e2e8f0;">
        <tbody>
            
            {{-- Encabezado con padding vertical y horizontal real en la celda --}}
            <tr>
                <td align="center" style="background: linear-gradient(135deg, #14532d, #166534); padding: 32px 24px;">
                    <div style="font-size: 40px; margin-bottom: 6px;">⚽</div>
                    <h1 style="color: #ffffff; font-size: 24px; font-weight: 900; margin: 0; tracking-tight: -0.5px;">MatchDay</h1>
                    <p style="color: #bbf7d0; font-size: 11px; font-weight: bold; text-transform: uppercase; tracking-wider: 1px; margin: 4px 0 0 0;">Resultado del Partido</p>
                </td>
            </tr>

            {{-- Cuerpo del Correo --}}
            <tr>
                <td style="padding: 32px 24px;">
                    <p style="color: #475569; font-size: 14px; font-weight: 500; margin: 0 0 20px 0; text-align: left;">
                        Hola, aquí tienes el resultado del partido:
                    </p>

                    {{-- Badge de Fase --}}
                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 24px;">
                        <tr>
                            <td align="center" style="background-color: #f0fdf4; border: 1px solid #dcfce7; padding: 4px 14px; border-radius: 20px;">
                                <span style="color: #166534; font-size: 11px; font-weight: 800; text-transform: uppercase; tracking-wide: 0.5px;">
                                    {{ \App\Helpers\StatusHelper::stage($match->stage) }}
                                </span>
                            </td>
                        </tr>
                    </table>

                    {{-- Marcador: Tabla interna con padding de 24px en su celda contenedora --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 12px; margin-bottom: 24px;">
                        <tbody>
                            <tr>
                                <td style="padding: 24px 16px;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            {{-- Local --}}
                                            <td width="40%" align="center" style="vertical-align: middle;">
                                                <div style="font-size: 15px; font-weight: 800; color: #1e293b; line-height: 1.2;">{{ $match->homeTeam->name }}</div>
                                                <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-top: 3px;">Local</div>
                                            </td>

                                            {{-- Score --}}
                                            <td width="20%" align="center" style="vertical-align: middle; font-size: 36px; font-weight: 900; color: #16a34a; font-family: monospace;">
                                                {{ $match->home_score }}—{{ $match->away_score }}
                                            </td>

                                            {{-- Visitante --}}
                                            <td width="40%" align="center" style="vertical-align: middle;">
                                                <div style="font-size: 15px; font-weight: 800; color: #1e293b; line-height: 1.2;">{{ $match->awayTeam->name }}</div>
                                                <div style="font-size: 10px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-top: 3px;">Visitante</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            {{-- Penales --}}
                            @if(!is_null($match->home_penalties))
                            <tr>
                                <td align="center" style="background-color: #eff6ff; border-top: 1px solid #dbeafe; padding: 8px 12px; font-size: 12px; font-weight: 800; color: #2563eb; font-family: monospace;">
                                    Penales: {{ $match->home_penalties }} — {{ $match->away_penalties }}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- Tabla de Detalles Técnicos --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 28px;">
                        <tbody>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Torneo</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b;">{{ $match->tournament->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Fecha</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b; font-family: monospace;">{{ $match->played_at->format('d/m/Y H:i') }} hs</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Fase</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b;">{{ \App\Helpers\StatusHelper::stage($match->stage) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Ganador en eliminatorias directas --}}
                    @if(in_array($match->stage, ['round32', 'round16', 'quarter', 'semi', 'final']))
                        @php
                            $winner = !is_null($match->home_penalties)
                                ? ($match->home_penalties > $match->away_penalties ? $match->homeTeam->name : $match->awayTeam->name)
                                : ($match->home_score > $match->away_score ? $match->homeTeam->name : $match->awayTeam->name);
                        @endphp
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; margin-bottom: 28px; text-align: center;">
                            <tr>
                                <td style="padding: 16px;">
                                    <div style="font-size: 11px; font-weight: 800; color: #d97706; text-transform: uppercase; tracking-wide: 0.5px;">🏆 Avanza a la siguiente fase</div>
                                    <div style="font-size: 18px; font-weight: 900; color: #78350f; margin-top: 4px;">{{ $winner }}</div>
                                </td>
                            </tr>
                        </table>
                    @endif

                    {{-- Botón CTA Seguro --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td align="center">
                                <a href="{{ config('app.url') }}/admin/matches/{{ $match->id }}" target="_blank" style="display: inline-block; background-color: #16a34a; color: #ffffff; padding: 12px 28px; border-radius: 10px; font-size: 13px; font-weight: bold; text-transform: uppercase; tracking-wider: 0.5px; text-decoration: none;">
                                    Ver en MatchDay
                                </a>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>

            {{-- Footer --}}
            <tr>
                <td align="center" style="background-color: #f8fafc; padding: 24px; border-top: 1px solid #e2e8f0;">
                    <p style="font-size: 10px; font-weight: bold; text-transform: uppercase; tracking-wider: 0.5px; color: #94a3b8; margin: 0 0 4px 0;">
                        MatchDay &middot; Sistema de Gestión de Torneos
                    </p>
                    <p style="font-size: 9px; color: #cbd5e1; margin: 0;">
                        Copa MatchDay 2026 &middot; Aplicaciones Web Interactivas &middot; UASLP
                    </p>
                </td>
            </tr>

        </tbody>
    </table>

</body>
</html>