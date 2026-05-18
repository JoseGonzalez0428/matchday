<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body        { font-family: Arial, sans-serif; font-size: 12px; color: #1C1C1C; }
        .header     { border-bottom: 3px solid #1A6B3A; padding-bottom: 8px; margin-bottom: 16px; }
        .title      { font-size: 20px; font-weight: bold; color: #1A6B3A; }
        .subtitle   { font-size: 11px; color: #666; }
        table       { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th          { background: #1A6B3A; color: #fff; padding: 6px 8px; text-align: center; font-size: 11px; }
        td          { padding: 5px 8px; border-bottom: 1px solid #ddd; text-align: center; }
        tr:nth-child(even) { background: #D6EFD8; }
        .group-title { font-size: 14px; font-weight: bold; color: #1A3A6B; margin: 16px 0 6px; }
        .footer     { position: fixed; bottom: 0; width: 100%; border-top: 1px solid #ccc;
                      font-size: 9px; color: #999; text-align: center; padding-top: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">MatchDay — Tabla de Posiciones</div>
        <div class="subtitle">{{ $tournament->name }} · Generado el {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    @foreach($standings as $groupName => $teams)
        <div class="group-title">Grupo {{ $groupName }}</div>
        <table>
            <thead>
                <tr>
                    <th style="text-align:left;">#</th>
                    <th style="text-align:left;">Equipo</th>
                    <th>PJ</th><th>G</th><th>E</th><th>P</th>
                    <th>GF</th><th>GC</th><th>DG</th><th>Pts</th>
                </tr>
            </thead>
            <tbody>
                @foreach($teams as $pos => $row)
                    <tr>
                        <td>{{ $pos + 1 }}</td>
                        <td style="text-align:left;">{{ $row['team']->name }}</td>
                        <td>{{ $row['played'] }}</td>
                        <td>{{ $row['won'] }}</td>
                        <td>{{ $row['drawn'] }}</td>
                        <td>{{ $row['lost'] }}</td>
                        <td>{{ $row['gf'] }}</td>
                        <td>{{ $row['gc'] }}</td>
                        <td>{{ $row['gd'] > 0 ? '+' : '' }}{{ $row['gd'] }}</td>
                        <td><strong>{{ $row['points'] }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table></div>
    @endforeach

    <div class="footer">MatchDay · Sistema de Gestión de Torneos</div>
</body>
</html>