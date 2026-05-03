<x-mail::message>
# ¡Inscripción confirmada! 🎉

Hola **{{ $team->captain->name ?? 'Capitán' }}**,

Tu equipo **{{ $team->name }}** ha sido inscrito exitosamente en el torneo:

**{{ $tournament->name }}**
Inicio: {{ \Carbon\Carbon::parse($tournament->starts_at)->format('d/m/Y') }}

<x-mail::button url="{{ url('/captain/dashboard') }}" color="success">
Ver mi dashboard
</x-mail::button>

¡Mucha suerte en el torneo!

Saludos,
**El equipo de MatchDay** ⚽
</x-mail::message>