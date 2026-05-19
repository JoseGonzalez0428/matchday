<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 9px; color: #334155; padding: 20px 25px 40px 25px; }

        .header { border-bottom: 2px solid #16a34a; padding-bottom: 8px; margin-bottom: 14px; }
        .title   { font-size: 15px; font-weight: bold; color: #14532d; }
        .subtitle { font-size: 8px; color: #94a3b8; margin-top: 2px; }

        .group-title {
            font-size: 9px; font-weight: bold; color: #14532d;
            text-transform: uppercase; background: #f0fdf4;
            padding: 3px 8px; margin: 12px 0 4px;
            border-left: 3px solid #16a34a;
            page-break-after: avoid;
        }

        table { width: 100%; border-collapse: collapse; margin-bottom: 6px; page-break-inside: avoid; }
        th {
            background: #f8fafc; color: #475569; padding: 5px 6px;
            font-size: 8px; font-weight: bold; text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0; border-top: 1px solid #e2e8f0;
            text-align: center;
        }
        td { padding: 4px 6px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 8.5px; }
        tr:last-child td { border-bottom: none; }

        .col-team    { width: 32%; text-align: left; font-weight: 600; }
        .col-score   { width: 10%; text-align: center; font-weight: bold; font-family: monospace; }
        .col-date    { width: 18%; text-align: center; color: #64748b; font-family: monospace; font-size: 8px; }
        .col-status  { width: 10%; text-align: center; }

        .status-finished  { color: #166534; background: #dcfce7; padding: 1px 4px; border-radius: 3px; font-size: 7px; font-weight: bold; }
        .status-scheduled { color: #854d0e; background: #fef9c3; padding: 1px 4px; border-radius: 3px; font-size: 7px; font-weight: bold; }
        .status-live      { color: #991b1b; background: #fee2e2; padding: 1px 4px; border-radius: 3px; font-size: 7px; font-weight: bold; }

        .footer { position: fixed; bottom: 0; left: 0; width: 100%; border-top: 1px solid #e2e8f0; font-size: 7px; color: #94a3b8; text-align: center; padding: 4px 0; background: white; }
    </style>
</head>
<body>

<div class="header">
    <div class="title">MatchDay &mdash; Fixture</div>
    <div class="subtitle">{{ $tournament->name }} &middot; Generado el {{ now()->format('d/m/Y H:i') }}</div>
</div>

@foreach($matches as $groupName => $groupMatches)
    <div class="group-title">{{ $groupName ?? 'Fase Eliminatoria' }}</div>
    <table>
        <thead>
            <tr>
                <th class="col-team" style="text-align:left;">Local</th>
                <th class="col-score">Resultado</th>
                <th class="col-team" style="text-align:left;">Visitante</th>
                <th class="col-date">Fecha</th>
                <th class="col-status">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($groupMatches as $match)
            <tr>
                <td class="col-team">{{ $match->homeTeam?->name ?? '(Eliminado)' }}</td>
                <td class="col-score">
                    @if($match->status === 'finished')
                        {{ $match->home_score }} - {{ $match->away_score }}
                        @if(!is_null($match->home_penalties))
                            <br><span style="font-size:7px;color:#2563eb;">({{ $match->home_penalties }}-{{ $match->away_penalties }} pen)</span>
                        @endif
                    @else
                        <span style="color:#cbd5e1;">vs</span>
                    @endif
                </td>
                <td class="col-team">{{ $match->awayTeam?->name ?? '(Eliminado)' }}</td>
                <td class="col-date">{{ $match->played_at->format('d/m/Y H:i') }}</td>
                <td class="col-status">
                    <span class="status-{{ $match->status }}">
                        {{ \App\Helpers\StatusHelper::match($match->status) }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endforeach

<div class="footer">MatchDay &middot; Sistema de Gestion de Torneos &middot; {{ $tournament->name }}</div>

</body>
</html>