@extends('layouts.admin')

@section('title', 'Equipos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-green-800">👕 Equipos</h1>
    <div class="flex gap-2">
        <form method="GET" action="{{ route('admin.teams.index') }}" class="flex gap-2">
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Buscar equipo..."
                   class="border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 w-48">
            <button type="submit"
                    class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm hover:bg-green-200">
                🔍
            </button>
            @if($search)
            <a href="{{ route('admin.teams.index') }}"
               class="px-4 py-2 rounded-lg border text-sm hover:bg-gray-50 text-gray-600">
                ✕
            </a>
            @endif
        </form>
        <a href="{{ route('admin.teams.create') }}"
           class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800">
            + Nuevo equipo
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="text-left px-4 py-3">Equipo</th>
                <th class="text-left px-4 py-3">País</th>
                <th class="text-left px-4 py-3">Capitán</th>
                <th class="text-left px-4 py-3">Jugadores</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teams as $team)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        @if($team->shield_url)
                            <img src="{{ Storage::url($team->shield_url) }}"
                                 class="w-8 h-8 rounded-full object-cover">
                        @else
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center text-green-700 font-bold text-xs">
                                {{ strtoupper(substr($team->name, 0, 2)) }}
                            </div>
                        @endif
                        <span class="font-medium">{{ $team->name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $team->country ?? '—' }}</td>
                <td class="px-4 py-3">{{ $team->captain->name ?? '—' }}</td>
                <td class="px-4 py-3 text-center">{{ $team->players_count ?? 0 }}</td>
                <td class="px-4 py-3 flex gap-2 justify-center">
                    <a href="{{ route('admin.teams.show', $team) }}"
                       class="text-green-700 hover:underline">Ver</a>
                    <a href="{{ route('admin.teams.edit', $team) }}"
                       class="text-blue-600 hover:underline">Editar</a>
                    <form method="POST" action="{{ route('admin.teams.destroy', $team) }}"
                          onsubmit="return confirm('¿Eliminar este equipo?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                    No hay equipos registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-4 py-3 border-t flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Mostrando {{ $teams->firstItem() }}-{{ $teams->lastItem() }} de {{ $teams->total() }} equipos
        </p>
        {{ $teams->links() }}
    </div>
</div>
@endsection