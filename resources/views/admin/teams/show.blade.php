@extends('layouts.admin')

@section('title', $team->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado de Perfil del Equipo --}}
    <div class="flex flex-col lg:flex-row justify-between items-center bg-white p-6 rounded-2xl border border-gray-100 shadow-sm gap-4">
        <div class="flex flex-col sm:flex-row items-center text-center sm:text-left gap-4">
            @if($team->shield_url)
                <img src="{{ Storage::url($team->shield_url) }}"
                     class="w-16 h-16 rounded-full object-cover border-2 border-green-100 shadow-sm p-0.5 bg-white cursor-pointer hover:opacity-80 transition-opacity"
                     onclick="openModal()">
            @else
                <div class="w-16 h-16 rounded-full bg-green-50 text-green-700 font-black text-xl flex items-center justify-center border border-green-100 shadow-inner">
                    {{ strtoupper(substr($team->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <h1 class="text-2xl md:text-3xl font-black text-gray-800 tracking-tight">{{ $team->name }}</h1>
                <p class="text-sm font-medium text-gray-400 mt-1">
                    {{ $team->country ?? 'Sin país' }} <span class="text-gray-200 mx-1">•</span> Capitán: <span class="text-gray-600 font-bold">{{ $team->captain->name ?? '—' }}</span>
                </p>
            </div>
        </div>

        {{-- Acciones del Perfil --}}
        <div class="flex flex-wrap items-center justify-center gap-2.5 w-full lg:w-auto">
            <a href="{{ route('admin.teams.players.index', $team) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-green-700 hover:bg-green-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition-all">
                👤 Jugadores ({{ $team->players->count() }})
            </a>
            <a href="{{ route('admin.teams.edit', $team) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center border border-gray-200 bg-white hover:bg-gray-50 text-gray-600 text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-sm">
                Editar
            </a>
            <button type="button"
                    onclick="confirmDelete('{{ route('admin.teams.destroy', $team) }}', '¿Eliminar el equipo {{ $team->name }}? Esta acción no se puede deshacer.')"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border border-rose-200 bg-white hover:bg-rose-50 text-rose-600 text-xs font-bold px-4 py-2.5 rounded-xl transition-all shadow-sm">
                Eliminar
            </button>
        </div>
    </div>

    {{-- Bloques KPI Estadísticos del Equipo --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">País</p>
            <p class="text-base font-black text-gray-700 mt-0.5">{{ $team->country ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Capitán</p>
            <p class="text-base font-black text-gray-700 mt-0.5">{{ $team->captain->name ?? '—' }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 shadow-sm">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Jugadores registrados</p>
            <p class="text-base font-black text-gray-700 mt-0.5">{{ $team->players->count() }}</p>
        </div>
    </div>

    {{-- Torneos en los que participa --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex items-center gap-2 mb-4 border-b border-gray-50 pb-3">
            <span class="text-xl">🏆</span>
            <h2 class="text-lg font-bold text-gray-800 tracking-tight">Torneos</h2>
        </div>

        @if($team->groups->isEmpty())
            <div class="text-center py-8 bg-slate-50/50 border border-dashed rounded-2xl">
                <p class="text-sm text-gray-400 font-medium">Este equipo no está inscrito en ningún torneo.</p>
            </div>
        @else
            <div class="border border-gray-100 rounded-2xl overflow-hidden shadow-sm bg-white">
                <div class="overflow-x-auto">
                    <table class="w-full text-xs md:text-sm text-left">
                        <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-[10px] md:text-xs">
                            <tr>
                                <th class="px-6 py-4">Torneo</th>
                                <th class="px-6 py-4">Grupo</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4 text-center">Ver</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($team->groups as $group)
                            <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.tournaments.show', $group->tournament) }}'">
                                <td class="px-6 py-4 font-bold text-gray-800 group-hover:text-green-700 transition-colors">
                                    {{ $group->tournament->name }}
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-semibold">
                                    Grupo {{ $group->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold tracking-wide uppercase
                                        {{ $group->tournament->status === 'active'   ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : '' }}
                                        {{ $group->tournament->status === 'draft'    ? 'bg-amber-50 text-amber-700 border border-amber-100' : '' }}
                                        {{ $group->tournament->status === 'finished' ? 'bg-gray-50 text-gray-500 border border-gray-200' : '' }}">
                                        {{ ucfirst($group->tournament->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center" onclick="event.stopPropagation()">
                                    <a href="{{ route('admin.tournaments.show', $group->tournament) }}"
                                       class="text-green-600 hover:text-green-800 font-bold text-xs hover:underline transition-colors">
                                        Ver torneo
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

</div>

{{-- Modal Escudo Ampliado --}}
<div id="shield-modal"
     class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none opacity-0 transition-opacity duration-300"
     onclick="closeModal()">
    <div class="absolute inset-0 bg-gray-950/80 backdrop-blur-xs"></div>
    <div class="relative transform scale-75 transition-transform duration-300" 
         id="shield-modal-content"
         onclick="event.stopPropagation()">
        <img src="{{ $team->shield_url ? Storage::url($team->shield_url) : '' }}"
             class="max-w-sm max-h-screen rounded-2xl shadow-2xl object-contain border-4 border-white/10">
        <button onclick="closeModal()"
                class="absolute -top-3 -right-3 bg-white text-gray-500 hover:text-gray-800 rounded-full w-8 h-8 flex items-center justify-center shadow-lg font-mono text-sm border font-bold transition-colors">
            ✕
        </button>
    </div>
</div>

{{-- Script Nativo de Control del Modal --}}
<script>
function openModal() {
    const modal = document.getElementById('shield-modal');
    const content = document.getElementById('shield-modal-content');
    modal.classList.remove('pointer-events-none', 'opacity-0');
    modal.classList.add('pointer-events-auto', 'opacity-100');
    content.classList.remove('scale-75');
    content.classList.add('scale-100');
}

function closeModal() {
    const modal = document.getElementById('shield-modal');
    const content = document.getElementById('shield-modal-content');
    modal.classList.remove('pointer-events-auto', 'opacity-100');
    modal.classList.add('pointer-events-none', 'opacity-0');
    content.classList.remove('scale-100');
    content.classList.add('scale-75');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>
@endsection