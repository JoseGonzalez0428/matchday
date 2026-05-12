@extends('layouts.admin')

@section('title', 'Jugadores — ' . $team->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold text-green-800">👤 Jugadores</h1>
        <p class="text-gray-500 mt-1">{{ $team->name }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.teams.show', $team) }}"
           class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
            ← Volver al equipo
        </a>
        <a href="{{ route('admin.teams.players.create', $team) }}"
           class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800">
            + Nuevo jugador
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="text-left px-4 py-3">#</th>
                <th class="text-left px-4 py-3">Nombre</th>
                <th class="text-left px-4 py-3">Posición</th>
                <th class="text-left px-4 py-3">Nacionalidad</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($players as $player)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3 font-bold text-green-700">{{ $player->dorsal }}</td>
                <td class="px-4 py-3 font-medium">{{ $player->name }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs font-medium
                        {{ $player->position === 'GK' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $player->position === 'DEF' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $player->position === 'MID' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $player->position === 'FWD' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ $player->position }}
                    </span>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $player->nationality ?? '—' }}</td>
                <td class="px-4 py-3 flex gap-2 justify-center">
                    <a href="{{ route('admin.teams.players.edit', [$team, $player]) }}"
                       class="text-blue-600 hover:underline">Editar</a>
                    <form method="POST"
                          action="{{ route('admin.teams.players.destroy', [$team, $player]) }}"
                          onsubmit="return confirm('¿Eliminar este jugador?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                    No hay jugadores registrados en este equipo.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection