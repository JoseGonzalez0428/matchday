@extends('layouts.admin')

@section('title', $team->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-4">
        @if($team->shield_url)
            <img src="{{ Storage::url($team->shield_url) }}"
                 class="w-16 h-16 rounded-full object-cover border-2 border-green-200">
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
    <div class="flex gap-2">
        <a href="{{ route('admin.teams.edit', $team) }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
            Editar
        </a>
        <form method="POST" action="{{ route('admin.teams.destroy', $team) }}"
              onsubmit="return confirm('¿Eliminar este equipo?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                Eliminar
            </button>
        </form>
    </div>
</div>

{{-- Plantilla --}}
<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-700">Plantilla de jugadores</h2>
        <span class="text-sm text-gray-500">{{ $team->players->count() }} jugadores</span>
    </div>

    @if($team->players->isEmpty())
        <div class="px-6 py-8 text-center text-gray-400">
            No hay jugadores registrados en este equipo.
        </div>
    @else
        <table class="w-full text-sm">
            <thead class="bg-green-50 text-green-800">
                <tr>
                    <th class="text-left px-4 py-3">#</th>
                    <th class="text-left px-4 py-3">Nombre</th>
                    <th class="text-left px-4 py-3">Posición</th>
                    <th class="text-left px-4 py-3">Nacionalidad</th>
                </tr>
            </thead>
            <tbody>
                @foreach($team->players->sortBy('dorsal') as $player)
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
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection