@extends('layouts.admin')

@section('title', 'Jugadores — ' . $team->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">👤</span> Jugadores
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">{{ $team->name }}</p>
        </div>
        <div class="flex flex-wrap items-center justify-center gap-2.5 w-full sm:w-auto">
            <a href="{{ route('admin.teams.show', $team) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all whitespace-nowrap">
                ← Volver al equipo
            </a>
            <a href="{{ route('admin.teams.players.create', $team) }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-green-700 hover:bg-green-800 text-white font-bold text-sm px-4 py-2.5 rounded-xl shadow-sm transition-all whitespace-nowrap">
                ➕ Nuevo jugador
            </a>
        </div>
    </div>

    {{-- Tabla de Jugadores --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-xs">
                    <tr>
                        <th class="px-6 py-4 w-20 text-center">#</th>
                        <th class="px-6 py-4">Nombre</th>
                        <th class="px-6 py-4">Posición</th>
                        <th class="px-6 py-4">Nacionalidad</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($players as $player)
                    <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.teams.players.show', [$team, $player]) }}'">
                        <td class="px-6 py-4 text-center font-mono font-black text-green-700 text-base">
                            {{ $player->dorsal }}
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-800 group-hover:text-green-700 transition-colors">
                            {{ $player->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border
                                {{ $player->position === 'GK'  ? 'bg-yellow-50 text-yellow-700 border-yellow-100' : '' }}
                                {{ $player->position === 'DEF' ? 'bg-blue-50 text-blue-700 border-blue-100' : '' }}
                                {{ $player->position === 'MID' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : '' }}
                                {{ $player->position === 'FWD' ? 'bg-rose-50 text-rose-700 border-rose-100' : '' }}">
                                {{ \App\Helpers\StatusHelper::position($player->position) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-medium">
                            {{ $player->nationality ?? '—' }}
                        </td>
                        <td class="px-6 py-4" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-3 font-semibold text-xs">
                                <a href="{{ route('admin.teams.players.show', [$team, $player]) }}"
                                   class="text-green-600 hover:text-green-800 hover:underline transition-colors">Ver</a>
                                <span class="text-gray-200">|</span>
                                <a href="{{ route('admin.teams.players.edit', [$team, $player]) }}"
                                   class="text-blue-600 hover:text-blue-800 hover:underline transition-colors">Editar</a>
                                <span class="text-gray-200">|</span>
                                <button type="button"
                                        onclick="confirmDelete('{{ route('admin.teams.players.destroy', [$team, $player]) }}', '¿Eliminar este jugador? Esta acción no se puede deshacer.')"
                                        class="text-red-500 hover:text-red-700 hover:underline transition-colors focus:outline-none">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-gray-400 bg-slate-50/30">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <span class="text-2xl">👥</span>
                                <span>No hay jugadores registrados en este equipo.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection