@extends('layouts.admin')

@section('title', 'Equipos')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal + Buscador --}}
    <div class="flex flex-col lg:flex-row justify-between items-center gap-4 pb-5 border-b border-gray-100">
        <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center gap-2 w-full lg:w-auto justify-center sm:justify-start">
            <span class="text-green-600">👕</span> Equipos
        </h1>
        
        <div class="flex flex-col sm:flex-row items-center gap-2.5 w-full lg:w-auto">
            {{-- Formulario de Búsqueda --}}
            <form method="GET" action="{{ route('admin.teams.index') }}" class="flex items-center gap-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar equipo..."
                           class="w-full bg-white border border-gray-200 rounded-xl pl-4 pr-10 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 shadow-sm">
                    @if($search)
                    <a href="{{ route('admin.teams.index') }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 font-bold text-xs">
                        ✕
                    </a>
                    @endif
                </div>
                <button type="submit" class="bg-green-50 hover:bg-green-100 text-green-700 font-bold border border-green-200 px-3.5 py-2 rounded-xl text-sm shadow-sm transition-all">
                    🔍
                </button>
            </form>

            <a href="{{ route('admin.teams.create') }}"
               class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-green-700 hover:bg-green-800 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
                ➕ Nuevo
            </a>
        </div>
    </div>

    {{-- Tabla de Equipos --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm text-left">
                <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-[10px] md:text-xs">
                    <tr>
                        <th class="px-6 py-4.5">Equipo</th>
                        <th class="px-6 py-4.5">País</th>
                        <th class="px-6 py-4.5">Capitán</th>
                        <th class="px-6 py-4.5 text-center">Jugadores</th>
                        <th class="px-6 py-4.5 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($teams as $team)
                    <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.teams.show', $team) }}'">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($team->shield_url)
                                    <img src="{{ Storage::url($team->shield_url) }}"
                                         class="w-8 h-8 rounded-full object-cover border bg-white p-0.5 shadow-sm">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-green-50 text-green-700 font-black text-[10px] flex items-center justify-center border border-green-100 shadow-inner">
                                        {{ strtoupper(substr($team->name, 0, 2)) }}
                                    </div>
                                @endif
                                <span class="font-bold text-gray-800 group-hover:text-green-700 transition-colors">{{ $team->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-medium">
                            {{ $team->country ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700 font-semibold">
                            {{ $team->captain->name ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-600">
                            {{ $team->players_count ?? 0 }}
                        </td>
                        <td class="px-6 py-4" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-3 font-semibold text-xs">
                                <a href="{{ route('admin.teams.show', $team) }}"
                                   class="text-green-600 hover:text-green-800 hover:underline transition-colors">
                                    Ver
                                </a>
                                <span class="text-gray-200">|</span>
                                <a href="{{ route('admin.teams.edit', $team) }}"
                                   class="text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                    Editar
                                </a>
                                <span class="text-gray-200">|</span>
                                <button type="button"
                                        onclick="confirmDelete('{{ route('admin.teams.destroy', $team) }}', '¿Eliminar este equipo? Esta acción no se puede deshacer.')"
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
                                <span class="text-2xl">👕</span>
                                <span>No hay equipos registrados.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($teams->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                Mostrando {{ $teams->firstItem() }}-{{ $teams->lastItem() }} de {{ $teams->total() }} equipos
            </p>
            {{ $teams->links() }}
        </div>
        @endif
    </div>
</div>
@endsection