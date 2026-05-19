<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Campeones! — MatchDay</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 0; -webkit-text-size-adjust: none; -ms-text-size-adjust: none; }
        table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        td { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
    </style>
</head>
<body style="background-color: #f8fafc; margin: 0; padding: 40px 0;">

    {{-- Tabla Maestra Rígida de 600px --}}
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" align="center" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05); border: 1px solid #e2e8f0;">
        <tbody>
            
            {{-- Encabezado --}}
            <tr>
                <td align="center" style="background: linear-gradient(135deg, #14532d, #166534); padding: 32px 24px;">
                    <div style="font-size: 40px; margin-bottom: 6px;">⚽</div>
                    <h1 style="color: #ffffff; font-size: 24px; font-weight: 900; margin: 0; tracking-tight: -0.5px;">MatchDay</h1>
                    <p style="color: #bbf7d0; font-size: 11px; font-weight: bold; text-transform: uppercase; tracking-wider: 1px; margin: 4px 0 0 0;">Sistema de Gestión de Torneos</p>
                </td>
            </tr>

            {{-- Contenido Core --}}
            <tr>
                <td style="padding: 32px 32px;">
                    <p style="color: #334155; font-size: 15px; font-weight: 500; margin: 0 0 20px 0; text-align: left;">
                        ¡Felicitaciones, <strong>{{ $team->captain->name ?? 'Capitán' }}</strong>!
                    </p>

                    {{-- Caja de Coronación de Campeón --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #fffbeb; border: 2px solid #fbbf24; border-radius: 16px; margin-bottom: 24px; text-align: center;">
                        <tbody>
                            <tr>
                                <td style="padding: 28px 16px;">
                                    <div style="font-size: 56px; margin-bottom: 4px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.05));">🏆</div>
                                    <div style="font-size: 11px; font-weight: 800; color: #d97706; text-transform: uppercase; tracking-wider: 1px;">Campeón</div>
                                    <div style="font-size: 28px; font-weight: 900; color: #78350f; margin: 6px 0 tracking-tight: -0.5px;">{{ $team->name }}</div>
                                    <div style="font-size: 13px; font-weight: bold; color: #b45309;">{{ $tournament->name }} &middot; {{ $tournament->edition }}</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Tarjeta del Marcador Técnico de la Final --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
                        <tbody>
                            <tr>
                                <td style="padding: 20px 12px;">
                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                        <tr>
                                            <td width="42%" align="center" style="vertical-align: middle;">
                                                <div style="font-size: 13px; font-weight: 800; color: #1e293b; line-height: 1.2;">{{ $final->homeTeam->name }}</div>
                                            </td>
                                            <td width="16%" align="center" style="vertical-align: middle; font-size: 26px; font-weight: 900; color: #16a34a; font-family: monospace;">
                                                {{ $final->home_score }}—{{ $final->away_score }}
                                            </td>
                                            <td width="42%" align="center" style="vertical-align: middle;">
                                                <div style="font-size: 13px; font-weight: 800; color: #1e293b; line-height: 1.2;">{{ $final->awayTeam->name }}</div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            @if(!is_null($final->home_penalties))
                            <tr>
                                <td align="center" style="background-color: #eff6ff; border-top: 1px solid #dbeafe; padding: 8px 12px; font-size: 11px; font-weight: 800; color: #2563eb; font-family: monospace;">
                                    Tanda de Penales: {{ $final->home_penalties }} — {{ $final->away_penalties }}
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>

                    {{-- Datos Informativos Consolidados --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 24px;">
                        <tbody>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Torneo</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b;">{{ $tournament->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Edición</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b;">{{ $tournament->edition }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Fecha de la final</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b; font-family: monospace;">{{ $final->played_at->format('d/m/Y') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="color: #475569; font-size: 14px; font-weight: 500; margin: 0 0 24px 0; text-align: left; line-height: 1.6;">
                        Tu equipo ha demostrado ser el mejor del torneo. ¡Enhorabuena por este histórico logro!
                    </p>

                    {{-- Botón de Enlace Directo --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td align="center">
                                <a href="{{ config('app.url') }}/admin/tournaments/{{ $tournament->id }}" target="_blank" style="display: inline-block; background-color: #16a34a; color: #ffffff; padding: 12px 28px; border-radius: 10px; font-size: 13px; font-weight: bold; text-transform: uppercase; tracking-wider: 0.5px;">
                                    Ver torneo en MatchDay
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
                        Copa MatchDay 2026 &middot; Desarrollo Web Avanzado &middot; UASLP
                    </p>
                </td>
            </tr>

        </tbody>
    </table>

</body>
</html>