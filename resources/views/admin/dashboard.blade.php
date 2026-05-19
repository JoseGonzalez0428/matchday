@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado de Bienvenida --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">⚽</span> Dashboard
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                Bienvenido, <span class="text-gray-700 font-bold">{{ auth()->user()->name }}</span>
            </p>
        </div>
    </div>

    {{-- Torneos recientes --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6 flex flex-col sm:flex-row sm:justify-between sm:items-center border-b border-gray-50 gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800 tracking-tight">Torneos recientes</h2>
                <p class="text-xs text-gray-400">Historial y estado de las competiciones en la plataforma.</p>
            </div>
            <a href="{{ route('admin.tournaments.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-green-700 hover:bg-green-800 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-sm transition-all">
                ➕ Nuevo torneo
            </a>
        </div>

        @if($tournaments->isEmpty())
            <div class="text-center py-12 bg-slate-50/30">
                <p class="text-sm text-gray-400 font-medium">No hay torneos registrados aún.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-xs md:text-sm text-left">
                    <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-[10px] md:text-xs">
                        <tr>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Edición</th>
                            <th class="px-6 py-4">Estado</th>
                            <th class="px-6 py-4">Inicio</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($tournaments as $tournament)
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.tournaments.show', $tournament) }}'">
                            <td class="px-6 py-4 font-bold text-gray-800 group-hover:text-green-700 transition-colors">
                                {{ $tournament->name }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 font-medium">
                                {{ $tournament->edition }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase
                                    {{ $tournament->status === 'active'   ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                                    {{ $tournament->status === 'draft'    ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                    {{ $tournament->status === 'finished' ? 'bg-gray-50 text-gray-500 border border-gray-200' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full
                                        {{ $tournament->status === 'active'   ? 'bg-emerald-500 animate-pulse' : '' }}
                                        {{ $tournament->status === 'draft'    ? 'bg-amber-500' : '' }}
                                        {{ $tournament->status === 'finished' ? 'bg-gray-400' : '' }}"></span>
                                    {{ \App\Helpers\StatusHelper::tournament($tournament->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 font-mono text-xs font-semibold">
                                {{ $tournament->starts_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                                <a href="{{ route('admin.tournaments.show', $tournament) }}"
                                   class="text-green-600 hover:text-green-800 font-semibold text-xs hover:underline transition-colors">
                                    Ver
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Gráficos Estadísticos --}}
    @if($activeTournament)
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-gray-100 pt-4">
            <div>
                <h2 class="text-lg font-bold text-gray-800 tracking-tight">Estadísticas del torneo</h2>
                <p class="text-xs text-gray-400">Visualización de datos de rendimiento general de los partidos.</p>
            </div>
            <select id="tournament-select"
                    class="w-full sm:w-auto bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all shadow-sm cursor-pointer md:min-w-64">
                @foreach($tournaments as $t)
                    <option value="{{ $t->id }}" {{ $t->id === $activeTournament->id ? 'selected' : '' }}>
                        {{ $t->name }} {{ $t->edition }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Línea: Goles por jornada --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col justify-between">
                <div class="mb-4">
                    <h3 class="font-bold text-gray-800 text-sm tracking-tight flex items-center gap-1.5">
                        <span>📈</span> Goles por jornada
                    </h3>
                </div>
                <div class="w-full relative">
                    <canvas id="goalsChart" height="140"></canvas>
                </div>
            </div>

            {{-- Distribución de resultados --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col items-center justify-between">
                <div class="w-full mb-4">
                    <h3 class="font-bold text-gray-800 text-sm tracking-tight flex items-center gap-1.5">
                        <span>📊</span> Distribución de resultados
                    </h3>
                </div>
                <div class="flex items-center justify-center p-2" style="width: 240px; height: 240px;">
                    <canvas id="resultsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Lógica e inicialización de Chart.js --}}
    <script>
    let goalsChart = null;
    let resultsChart = null;

    async function loadCharts(tournamentId) {
        const res = await fetch(`/admin/tournaments/${tournamentId}/chart-data`);
        const data = await res.json();

        if (goalsChart) goalsChart.destroy();
        if (resultsChart) resultsChart.destroy();

        // Goles por Jornada
        goalsChart = new Chart(document.getElementById('goalsChart'), {
            type: 'line',
            data: {
                labels: data.goals_by_day.map(d => d.label),
                datasets: [{
                    label: 'Goles',
                    data: data.goals_by_day.map(d => d.value),
                    borderColor: '#16a34a',
                    backgroundColor: 'rgba(22,163,74,0.06)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#16a34a',
                    pointHoverRadius: 5,
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: { 
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [4, 4], color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });

        // Distribución de Resultados
        resultsChart = new Chart(document.getElementById('resultsChart'), {
            type: 'doughnut',
            data: {
                labels: ['Victoria local', 'Victoria visitante', 'Empate'],
                datasets: [{
                    data: [data.results.home, data.results.away, data.results.draw],
                    backgroundColor: ['#16a34a', '#2563eb', '#94a3b8'],
                    borderWidth: 4,
                    borderColor: '#ffffff',
                    hoverOffset: 4
                }]
            },
            options: { 
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 11, weight: 'bold' }, padding: 15 }
                    }
                }
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const select = document.getElementById('tournament-select');
        loadCharts(select.value);
        select.addEventListener('change', () => loadCharts(select.value));
    });
    </script>
    @endif

</div>

@endsection