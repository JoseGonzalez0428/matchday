@extends('layouts.admin')

@section('title', $team->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
        @if($team->shield_url)
            <img src="{{ Storage::url($team->shield_url) }}"
            class="w-16 h-16 rounded-full object-cover border-2 border-green-200 cursor-pointer hover:opacity-90 transition-opacity"
            onclick="openModal()">
        @else
            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-xl">
                {{ strtoupper(substr($team->name, 0, 2)) }}
            </div>
        @endif
        <div>
            <h1 class="text-3xl font-bold text-green-800">{{ $team->name }}</h1>
            <p class="text-gray-500">{{ $team->country ?? 'Sin país' }} · Capitán: {{ $team->captain->name ?? '—' }}</p>
        </div>
    </div>
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.teams.players.index', $team) }}"
           class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800">
            👤 Jugadores ({{ $team->players->count() }})
        </a>
        <a href="{{ route('admin.teams.edit', $team) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Editar
        </a>
        <button type="button"
                onclick="confirmDelete('{{ route('admin.teams.destroy', $team) }}', '¿Eliminar el equipo {{ $team->name }}? Esta acción no se puede deshacer.')"
                class="text-red-600 hover:underline">
            Eliminar
        </button>
    </div>
</div>

{{-- Info del equipo --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase">País</p>
        <p class="font-bold text-gray-700 mt-1">{{ $team->country ?? '—' }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase">Capitán</p>
        <p class="font-bold text-gray-700 mt-1">{{ $team->captain->name ?? '—' }}</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-xs text-gray-500 uppercase">Jugadores registrados</p>
        <p class="font-bold text-gray-700 mt-1">{{ $team->players->count() }}</p>
    </div>
</div>

{{-- Torneos en los que participa --}}
<div class="bg-white rounded-xl shadow p-6">
    <h2 class="text-xl font-bold text-gray-700 mb-4">Torneos</h2>
    @if($team->groups->isEmpty())
        <p class="text-gray-400 text-sm">Este equipo no está inscrito en ningún torneo.</p>
    @else
        <div class="overflow-x-auto"><table class="w-full text-sm">
            <thead class="bg-green-50 text-green-800">
                <tr>
                    <th class="text-left px-4 py-2">Torneo</th>
                    <th class="text-left px-4 py-2">Grupo</th>
                    <th class="text-left px-4 py-2">Estado</th>
                    <th class="px-4 py-2">Ver</th>
                </tr>
            </thead>
            <tbody>
                @foreach($team->groups as $group)
                <tr class="border-t hover:bg-gray-50 cursor-pointer" onclick="window.location='{{ route('admin.tournaments.show', $group->tournament) }}'">
                    <td class="px-4 py-3 font-medium">{{ $group->tournament->name }}</td>
                    <td class="px-4 py-3">Grupo {{ $group->name }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $group->tournament->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $group->tournament->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $group->tournament->status === 'finished' ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ ucfirst($group->tournament->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.tournaments.show', $group->tournament) }}"
                           class="text-green-700 hover:underline text-xs">Ver torneo</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table></div>
    @endif
</div>

{{-- Modal escudo --}}
<div id="shield-modal"
     class="fixed inset-0 flex items-center justify-center z-50 pointer-events-none opacity-0 transition-opacity duration-300"
     onclick="closeModal()">
    <div class="absolute inset-0 bg-black bg-opacity-75"></div>
    <div class="relative transform scale-75 transition-transform duration-300" 
         id="shield-modal-content"
         onclick="event.stopPropagation()">
        <img src="{{ $team->shield_url ? Storage::url($team->shield_url) : '' }}"
             class="max-w-sm max-h-screen rounded-2xl shadow-2xl object-contain">
        <button onclick="closeModal()"
                class="absolute -top-3 -right-3 bg-white rounded-full w-8 h-8 flex items-center justify-center text-gray-600 hover:text-gray-900 shadow-lg">
            ✕
        </button>
    </div>
</div>

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

// Cerrar con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeModal();
});
</script>
@endsection