@extends('layouts.captain')

@section('title', 'Dashboard Capitán')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-green-800">⚽ Mi Dashboard</h1>
    <p class="text-gray-500 mt-1">Bienvenido, {{ auth()->user()->name }}</p>
</div>

@if(!$team)
    <div class="bg-yellow-50 border border-yellow-300 text-yellow-800 px-6 py-4 rounded-xl">
        No tienes un equipo asignado aún. Contacta al administrador.
    </div>
@else
{{-- Info del equipo --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4 flex items-center gap-4">
        @if($team->shield_url)
            <img src="{{ Storage::url($team->shield_url) }}"
                 class="w-12 h-12 rounded-full object-cover">
        @else
            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold">
                {{ strtoupper(substr($team->name, 0, 2)) }}
            </div>
        @endif
        <div>
            <p class="text-xs text-gray-500">Mi equipo</p>
            <p class="font-bold text-gray-800">{{ $team->name }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase">Jugadores</p>
        <p class="text-2xl font-bold text-green-700">{{ $team->players->count() }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase">Próximo partido</p>
        <p class="font-bold text-gray-800">
            {{ $nextMatch ? $nextMatch->played_at->format('d/m/Y H:i') : 'Sin partidos' }}
        </p>
    </div>
</div>

{{-- Próximo partido --}}
@if($nextMatch)
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">Próximo Partido</h2>
    <div class="flex items-center justify-between">
        <div class="text-center w-1/3">
            <p class="text-xl font-bold {{ $nextMatch->home_team_id === $team->id ? 'text-green-700' : 'text-gray-700' }}">
                {{ $nextMatch->homeTeam->name }}
            </p>
            <p class="text-xs text-gray-400">Local</p>
        </div>
        <div class="text-center">
            <p class="text-2xl font-bold text-gray-400">vs</p>
            <p class="text-sm text-gray-500 mt-1">{{ $nextMatch->played_at->format('d/m/Y H:i') }}</p>
        </div>
        <div class="text-center w-1/3">
            <p class="text-xl font-bold {{ $nextMatch->away_team_id === $team->id ? 'text-green-700' : 'text-gray-700' }}">
                {{ $nextMatch->awayTeam->name }}
            </p>
            <p class="text-xs text-gray-400">Visitante</p>
        </div>
    </div>
</div>
@endif

{{-- Análisis IA --}}
@if($nextMatch && isset($analysis) && $analysis)
<div class="bg-white rounded-xl shadow p-6 mb-6 border-l-4 border-green-500">
    <div class="flex items-center gap-2 mb-3">
        <span class="text-2xl">🤖</span>
        <h2 class="text-xl font-bold text-gray-700">Análisis del próximo partido</h2>
        <span class="ml-auto text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">
            Powered by Gemini AI
        </span>
    </div>
    <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
        {{ $analysis }}
    </div>
</div>
@endif

{{-- Últimos resultados --}}
@if($recentMatches->isNotEmpty())
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">Últimos Resultados</h2>
    @foreach($recentMatches as $match)
    <div class="flex items-center justify-between border-b py-3 last:border-0">
        <span class="w-1/3 text-right font-medium
            {{ $match->home_team_id === $team->id ? 'text-green-700' : 'text-gray-700' }}">
            {{ $match->homeTeam->name }}
        </span>
        <span class="mx-4 font-bold text-gray-800">
            {{ $match->home_score }} - {{ $match->away_score }}
        </span>
        <span class="w-1/3 font-medium
            {{ $match->away_team_id === $team->id ? 'text-green-700' : 'text-gray-700' }}">
            {{ $match->awayTeam->name }}
        </span>
    </div>
    @endforeach
</div>
@endif

{{-- Gráficos --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Rendimiento del equipo</h3>
        <canvas id="performanceChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Top goleadores</h3>
        <canvas id="scorersChart" height="200"></canvas>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const won   = {{ $recentMatches->filter(fn($m) => ($m->home_team_id === $team->id && $m->home_score > $m->away_score) || ($m->away_team_id === $team->id && $m->away_score > $m->home_score))->count() }};
    const drawn = {{ $recentMatches->filter(fn($m) => $m->home_score === $m->away_score)->count() }};
    const lost  = {{ $recentMatches->filter(fn($m) => ($m->home_team_id === $team->id && $m->home_score < $m->away_score) || ($m->away_team_id === $team->id && $m->away_score < $m->home_score))->count() }};

    new Chart(document.getElementById('performanceChart'), {
        type: 'bar',
        data: {
            labels: ['Victorias', 'Empates', 'Derrotas'],
            datasets: [{
                data: [won, drawn, lost],
                backgroundColor: ['#15803d', '#ca8a04', '#dc2626'],
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });

    const scorers = @json($team->players->map(fn($p) => ['name' => $p->name, 'goals' => $p->goals->count()])->sortByDesc('goals')->take(5)->values());

    new Chart(document.getElementById('scorersChart'), {
        type: 'bar',
        data: {
            labels: scorers.map(s => s.name),
            datasets: [{
                label: 'Goles',
                data: scorers.map(s => s.goals),
                backgroundColor: '#15803d',
            }]
        },
        options: { responsive: true, plugins: { legend: { display: false } } }
    });
});
</script>
@endif
@endsection