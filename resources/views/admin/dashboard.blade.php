@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-green-800">⚽ Dashboard</h1>
    <p class="text-gray-500 mt-1">Bienvenido, {{ auth()->user()->name }}</p>
</div>

{{-- Torneos recientes --}}
<div class="bg-white rounded-xl shadow p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold text-gray-700">Torneos recientes</h2>
        <a href="{{ route('admin.tournaments.create') }}"
           class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800">
            + Nuevo torneo
        </a>
    </div>

    @if($tournaments->isEmpty())
        <p class="text-gray-400 text-sm">No hay torneos registrados aún.</p>
    @else
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead class="bg-green-50 text-green-800">
                <tr>
                    <th class="text-left px-4 py-2">Nombre</th>
                    <th class="text-left px-4 py-2">Edición</th>
                    <th class="text-left px-4 py-2">Estado</th>
                    <th class="text-left px-4 py-2">Inicio</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tournaments as $tournament)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-3 font-medium">{{ $tournament->name }}</td>
                    <td class="px-4 py-3">{{ $tournament->edition }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $tournament->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $tournament->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $tournament->status === 'finished' ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ \App\Helpers\StatusHelper::tournament($tournament->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">{{ $tournament->starts_at->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('admin.tournaments.show', $tournament) }}"
                           class="text-green-700 hover:underline">Ver</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    @endif
</div>

{{-- Gráficos --}}
@if($activeTournament)
<div class="flex flex-wrap items-center justify-between gap-3 mb-4">
    <h2 class="text-lg font-bold text-gray-700">Estadísticas del torneo</h2>
    <select id="tournament-select"
            class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
        @foreach($tournaments as $t)
            <option value="{{ $t->id }}" {{ $t->id === $activeTournament->id ? 'selected' : '' }}>
                {{ $t->name }} {{ $t->edition }}
            </option>
        @endforeach
    </select>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Goles por jornada</h3>
        <canvas id="goalsChart" height="200"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="font-bold text-gray-700 mb-4">Distribución de resultados</h3>
        <canvas id="resultsChart" height="200"></canvas>
    </div>
</div>

<script>
let goalsChart = null;
let resultsChart = null;

async function loadCharts(tournamentId) {
    const res = await fetch(`/admin/tournaments/${tournamentId}/chart-data`);
    const data = await res.json();

    // Destruir gráficas anteriores si existen
    if (goalsChart) goalsChart.destroy();
    if (resultsChart) resultsChart.destroy();

    goalsChart = new Chart(document.getElementById('goalsChart'), {
        type: 'line',
        data: {
            labels: data.goals_by_day.map(d => d.label),
            datasets: [{
                label: 'Goles',
                data: data.goals_by_day.map(d => d.value),
                borderColor: '#15803d',
                backgroundColor: 'rgba(21,128,61,0.1)',
                tension: 0.3,
                fill: true,
            }]
        },
        options: { responsive: true }
    });

    resultsChart = new Chart(document.getElementById('resultsChart'), {
        type: 'doughnut',
        data: {
            labels: ['Victoria local', 'Victoria visitante', 'Empate'],
            datasets: [{
                data: [data.results.home, data.results.away, data.results.draw],
                backgroundColor: ['#15803d', '#1d4ed8', '#9ca3af'],
            }]
        },
        options: { responsive: true }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    const select = document.getElementById('tournament-select');
    loadCharts(select.value);
    select.addEventListener('change', () => loadCharts(select.value));
});
</script>
@endif
@endsection