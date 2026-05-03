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
        .status-finished  { color: #15803d; font-weight: bold; }
        .status-scheduled { color: #ca8a04; }
        .status-live      { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">⚽ MatchDay — Fixture</div>
        <div class="subtitle">{{ $tournament->name }} · Generado el {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    @foreach($matches as $groupName => $groupMatches)
        <div class="group-title">{{ $groupName ?? 'Fase Eliminatoria' }}</div>
        <table>
            <thead>
                <tr>
                    <th style="text-align:left;">Local</th>
                    <th>Resultado</th>
                    <th style="text-align:left;">Visitante</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupMatches as $match)
                    <tr>
                        <td style="text-align:left;">{{ $match->homeTeam->name }}</td>
                        <td>
                            @if($match->status === 'finished')
                                {{ $match->home_score }} - {{ $match->away_score }}
                            @else
                                vs
                            @endif
                        </td>
                        <td style="text-align:left;">{{ $match->awayTeam->name }}</td>
                        <td>{{ $match->played_at->format('d/m/Y H:i') }}</td>
                        <td class="status-{{ $match->status }}">{{ ucfirst($match->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach

    <div class="footer">MatchDay · Sistema de Gestión de Torneos</div>
</body>
</html>