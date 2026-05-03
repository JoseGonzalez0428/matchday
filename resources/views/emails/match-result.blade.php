<x-mail::message>
# Resultado del partido ⚽

Se ha registrado el resultado del siguiente partido:

<x-mail::table>
| Local | Resultado | Visitante |
|:------|:---------:|----------:|
| {{ $match->homeTeam->name }} | {{ $match->home_score }} - {{ $match->away_score }} | {{ $match->awayTeam->name }} |
</x-mail::table>

**Torneo:** {{ $match->tournament->name }}
**Fecha:** {{ \Carbon\Carbon::parse($match->played_at)->format('d/m/Y H:i') }}
**Fase:** {{ ucfirst($match->stage) }}

<x-mail::button url="{{ url('/captain/dashboard') }}" color="success">
Ver mi dashboard
</x-mail::button>

Saludos,
**El equipo de MatchDay** ⚽
</x-mail::message>