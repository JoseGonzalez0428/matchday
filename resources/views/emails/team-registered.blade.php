<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Equipo Registrado — MatchDay</title>
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
                    <p style="color: #bbf7d0; font-size: 11px; font-weight: bold; text-transform: uppercase; tracking-wider: 1px; margin: 4px 0 0 0;">Equipo Registrado</p>
                </td>
            </tr>

            {{-- Contenido Core --}}
            <tr>
                <td style="padding: 32px 24px;">
                    <p style="color: #1e293b; font-size: 15px; font-weight: bold; margin: 0 0 16px 0; text-align: left;">
                        ¡Bienvenido a MatchDay! Tu equipo ha sido registrado exitosamente.
                    </p>

                    {{-- Caja del Tarjetón del Equipo --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 12px; margin-bottom: 24px; text-align: center;">
                        <tbody>
                            <tr>
                                <td style="padding: 24px 16px;">
                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin-bottom: 12px;">
                                        <tr>
                                            <td align="center" style="width: 56px; height: 56px; background-color: #16a34a; border-radius: 50%; color: #ffffff; font-size: 20px; font-weight: 900; font-family: monospace;">
                                                {{ strtoupper(substr($team->name, 0, 2)) }}
                                            </td>
                                        </tr>
                                    </table>
                                    <div style="font-size: 20px; font-weight: 900; color: #1e293b; tracking-tight: -0.5px;">{{ $team->name }}</div>
                                    @if($team->country)
                                        <div style="font-size: 12px; font-weight: bold; color: #64748b; margin-top: 4px; text-transform: uppercase; tracking-wider: 0.5px;">{{ $team->country }}</div>
                                    @endif
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Desgloses Técnicos --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 24px;">
                        <tbody>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Capitán</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b;">{{ $team->captain->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Jugadores registrados</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b;">{{ $team->players()->count() }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #64748b; text-align: left;">Fecha de registro</td>
                                <td align="right" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: bold; color: #1e293b; font-family: monospace;">{{ $team->created_at->format('d/m/Y') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <p style="color: #64748b; font-size: 13px; font-weight: 500; margin: 0 0 24px 0; text-align: left; line-height: 1.5;">
                        Puedes acceder al sistema para ver los partidos y estadísticas de tu equipo.
                    </p>

                    {{-- Botón de Acción --}}
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td align="center">
                                <a href="{{ url('/') }}" target="_blank" style="display: inline-block; background-color: #16a34a; color: #ffffff; padding: 12px 28px; border-radius: 10px; font-size: 13px; font-weight: bold; text-transform: uppercase; tracking-wider: 0.5px;">
                                    Ir a MatchDay
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