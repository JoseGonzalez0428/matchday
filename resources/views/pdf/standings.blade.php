<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Estilos Base Estables para Renderizado PDF */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 10px; color: #334155; line-height: 1.4; padding: 30px 40px; }
        
        /* Encabezado Principal */
        .header { border-bottom: 2px solid #16a34a; padding-bottom: 8px; margin-bottom: 16px; }
        .title  { font-size: 18px; font-weight: bold; color: #14532d; tracking-tight: -0.5px; }
        .subtitle { font-size: 9px; color: #94a3b8; margin-top: 3px; font-weight: 500; }
        
        /* Tablas de Posiciones */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background: #ffffff; page-break-inside: avoid; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden; }
        th { background: #f8fafc; color: #475569; padding: 6px 8px; font-size: 9px; font-weight: bold; text-transform: uppercase; tracking-wide: 0.5px; border-bottom: 2px solid #e2e8f0; text-align: center; }
        td { padding: 6px 8px; border-bottom: 1px solid #f1f5f9; text-align: center; color: #475569; font-medium; }
        tr:nth-child(even) { background: #f8fafc/50; }
        tr:last-child td { border-bottom: none; }
        
        /* Fila de Clasificados (Top 2) */
        .row-qualified { background: #f0fdf4/60; }
        .pos-badge { background: #e2e8f0; color: #475569; font-weight: bold; padding: 1px 4px; border-radius: 4px; font-size: 8px; }
        .pos-badge-top { background: #dcfce7; color: #166534; font-weight: bold; padding: 1px 4px; border-radius: 4px; font-size: 8px; }
        
        /* Bloques de Grupos */
        .group-title { font-size: 11px; font-weight: bold; color: #14532d; text-transform: uppercase; background: #f0fdf4; padding: 4px 8px; margin: 16px 0 6px; border-left: 3px solid #16a34a; tracking-wide: 0.5px; page-break-inside: avoid; page-break-after: avoid; }
        
        /* Pie de Página Fijo */
        .footer { position: fixed; bottom: -10px; width: 100%; border-top: 1px solid #e2e8f0; font-size: 8px; color: #94a3b8; text-align: center; padding-top: 5px; font-weight: 500; }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <div class="title">MatchDay — Tabla de Posiciones</div>
        <div class="subtitle">{{ $tournament->name }} · Generado el {{ now()->format('d/m/Y H:i') }} hs</div>
    </div>

    {{-- Ciclo de Grupos --}}
    @foreach($standings as $groupName => $teams)
        <div class="group-title">Grupo {{ $groupName }}</div>
        <table>
            <thead>
                <tr>
                    <th style="text-align:left; width: 6%;">#</th>
                    <th style="text-align:left; width: 44%;">Equipo</th>
                    <th style="width: 7%;">PJ</th>
                    <th style="width: 7%;">G</th>
                    <th style="width: 7%;">E</th>
                    <th style="width: 7%;">P</th>
                    <th style="width: 7%;">GF</th>
                    <th style="width: 7%;">GC</th>
                    <th style="width: 7%;">DG</th>
                    <th style="width: 9%; background: #f1f5f9; color: #1e293b; font-weight: black;">Pts</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $pos => $row)
                    <tr class="{{ $pos < 2 ? 'row-qualified' : '' }}">
                        <td style="text-align:left;">
                            <span class="{{ $pos < 2 ? 'pos-badge-top' : 'pos-badge' }}">{{ $pos + 1 }}</span>
                        </td>
                        <td style="text-align:left; font-weight: bold; color: #1e293b;">{{ $row['team']->name }}</td>
                        <td>{{ $row['played'] }}</td>
                        <td style="font-weight: 600; color: #166534;">{{ $row['won'] }}</td>
                        <td style="font-weight: 600; color: #854d0e;">{{ $row['drawn'] }}</td>
                        <td style="font-weight: 600; color: #991b1b;">{{ $row['lost'] }}</td>
                        <td style="color: #64748b;">{{ $row['gf'] }}</td>
                        <td style="color: #64748b;">{{ $row['gc'] }}</td>
                        <td style="font-weight: bold; font-family: monospace; color: {{ $row['gd'] > 0 ? '#16a34a' : ($row['gd'] < 0 ? '#dc2626' : '#94a3b8') }}">
                            {{ $row['gd'] > 0 ? '+' : '' }}{{ $row['gd'] }}
                        </td>
                        <td style="background: #f8fafc; font-weight: black; color: #15803d; font-size: 11px;">{{ $row['points'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    {{-- Footer Fijo --}}
    <div class="footer">MatchDay · Sistema de Gestión de Torneos</div>
</body>
</html>