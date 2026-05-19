@extends('layouts.captain')

@section('title', 'Dashboard Capitán')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado de Bienvenida --}}
    <div class="mb-6 pb-5 border-b border-gray-100">
        <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
            <span class="text-green-600">⚽</span> Mi Dashboard
        </h1>
        <p class="text-sm font-medium text-gray-400 mt-1">
            Bienvenido, <span class="text-gray-700 font-bold">{{ auth()->user()->name }}</span>
        </p>
    </div>

    @if(!$team)
        <div class="bg-amber-50 border border-amber-200 text-amber-800 px-6 py-4 rounded-2xl text-sm font-medium shadow-xs">
            ⚠️ No tienes un equipo asignado aún. Contacta al administrador.
        </div>
    @else
    {{-- Info del equipo (KPI Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex items-center gap-4">
            @if($team->shield_url)
                <img src="{{ Storage::url($team->shield_url) }}" class="w-12 h-12 rounded-full object-cover border bg-white p-0.5 shadow-sm">
            @else
                <div class="w-12 h-12 rounded-full bg-green-50 text-green-700 font-black text-sm flex items-center justify-center border border-green-100 shadow-inner">
                    {{ strtoupper(substr($team->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Mi equipo</p>
                <p class="text-sm font-black text-gray-800 mt-0.5">{{ $team->name }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jugadores</p>
                <p class="text-xl font-black text-green-700 mt-0.5">{{ $team->players->count() }}</p>
            </div>
            <span class="text-xl bg-slate-50 p-2 rounded-xl border border-gray-100">👥</span>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Próximo partido</p>
                <p class="text-sm font-black text-gray-700 mt-1">
                    {{ $nextMatch ? $nextMatch->played_at->format('d/m/Y H:i') : 'Sin partidos' }}
                </p>
            </div>
            <span class="text-xl bg-slate-50 p-2 rounded-xl border border-gray-100">📅</span>
        </div>
    </div>

    {{-- Próximo partido --}}
    @if($nextMatch)
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 flex items-center gap-1.5">
            <span>⏱️</span> Próximo Partido
        </h2>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 bg-slate-50/60 border border-gray-100 p-6 rounded-2xl">
            <div class="w-full sm:flex-1 text-center sm:text-right">
                <p class="text-base font-black truncate {{ $nextMatch->home_team_id === $team->id ? 'text-green-700' : 'text-gray-700' }}">
                    {{ $nextMatch->homeTeam->name }}
                </p>
                <p class="text-[10px] font-bold tracking-wider uppercase text-gray-400 mt-0.5">Local</p>
            </div>
            
            <div class="flex flex-col items-center bg-white border px-4 py-2 rounded-xl shadow-xs min-w-[110px]">
                <p class="text-xs font-black tracking-widest text-gray-300 uppercase">VS</p>
                <p class="text-[10px] font-mono font-bold text-gray-500 mt-1">{{ $nextMatch->played_at->format('d/m/Y H:i') }}</p>
            </div>
            
            <div class="w-full sm:flex-1 text-center sm:text-left">
                <p class="text-base font-black truncate {{ $nextMatch->away_team_id === $team->id ? 'text-green-700' : 'text-gray-700' }}">
                    {{ $nextMatch->awayTeam->name }}
                </p>
                <p class="text-[10px] font-bold tracking-wider uppercase text-gray-400 mt-0.5">Visitante</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Análisis IA --}}
    @if($nextMatch && isset($analysis) && $analysis)
    <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-100 rounded-2xl p-5 shadow-xs">
        <div class="flex items-center justify-between gap-3 border-b border-green-200/50 pb-3 mb-3">
            <div class="flex items-center gap-2">
                <span class="text-xl">🤖</span>
                <h2 class="text-sm font-bold text-green-900 tracking-tight">Análisis del próximo partido</h2>
            </div>
            <span class="text-[10px] font-bold tracking-wider uppercase bg-green-200/60 text-green-700 px-2.5 py-1 rounded-full border border-green-300/40">
                Powered by Gemini AI
            </span>
        </div>
        <div class="bg-white border border-green-100/50 rounded-xl p-4 text-xs md:text-sm text-gray-600 leading-relaxed whitespace-pre-line shadow-inner">
            {{ $analysis }}
        </div>
    </div>
    @endif

    {{-- Últimos resultados --}}
    @if($recentMatches->isNotEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-4 flex items-center gap-1.5">
            <span>📊</span> Últimos Resultados
        </h2>
        <div class="border border-gray-100 rounded-2xl bg-white overflow-hidden divide-y divide-gray-100 shadow-xs">
            @foreach($recentMatches as $match)
            <div class="flex flex-col sm:flex-row items-center justify-between px-4 py-3.5 gap-2.5 hover:bg-slate-50/50 transition-colors">
                <span class="w-full sm:w-1/3 text-center sm:text-right text-xs md:text-sm font-bold truncate
                    {{ $match->home_team_id === $team->id ? 'text-green-700' : 'text-gray-600' }}">
                    {{ $match->homeTeam->name }}
                </span>
                <span class="inline-flex items-center justify-center bg-slate-100 font-mono font-black text-xs md:text-sm px-3 py-1 rounded-xl tracking-tight text-gray-800 shadow-inner">
                    {{ $match->home_score }} - {{ $match->away_score }}
                </span>
                <span class="w-full sm:w-1/3 text-center sm:text-left text-xs md:text-sm font-bold truncate
                    {{ $match->away_team_id === $team->id ? 'text-green-700' : 'text-gray-600' }}">
                    {{ $match->awayTeam->name }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Gráficos Estadísticos --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col justify-between">
            <h3 class="font-bold text-gray-800 text-sm tracking-tight mb-4 flex items-center gap-1.5">
                <span>📈</span> Rendimiento del equipo
            </h3>
            <div class="w-full relative">
                <canvas id="performanceChart" height="200"></canvas>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col justify-between">
            <h3 class="font-bold text-gray-800 text-sm tracking-tight mb-4 flex items-center gap-1.5">
                <span>⚽</span> Top goleadores
            </h3>
            <div class="w-full relative">
                <canvas id="scorersChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Inicialización de Gráficos --}}
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
                    backgroundColor: ['#16a34a', '#eab308', '#dc2626'],
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: { 
                responsive: true, 
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } }
                }
            }
        });

        const scorers = @json($team->players->map(fn($p) => ['name' => $p->name, 'goals' => $p->goals->count()])->sortByDesc('goals')->take(5)->values());

        new Chart(document.getElementById('scorersChart'), {
            type: 'bar',
            data: {
                labels: scorers.map(s => s.name),
                datasets: [{
                    label: 'Goles',
                    data: scorers.map(s => s.goals),
                    backgroundColor: '#16a34a',
                    borderRadius: 8,
                    maxBarThickness: 40
                }]
            },
            options: { 
                responsive: true, 
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { stepSize: 1 } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
    </script>
    @endif

</div>
@endsection