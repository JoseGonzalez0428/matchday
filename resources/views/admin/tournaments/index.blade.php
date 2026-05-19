@extends('layouts.admin')

@section('title', 'Torneos')

@section('content')
{{-- Encabezado Principal --}}
<div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6 pb-5 border-b border-gray-100">
    <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center gap-2">
        <span class="text-green-600">🏆</span> Torneos
    </h1>
    <a href="{{ route('admin.tournaments.create') }}"
       class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-green-700 hover:bg-green-800 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
        ➕ Nuevo torneo
    </a>
</div>

{{-- Tabla Principal de Torneos --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-xs md:text-sm text-left">
            <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-[10px] md:text-xs">
                <tr>
                    <th class="px-6 py-4.5">Nombre</th>
                    <th class="px-6 py-4.5">Edición</th>
                    <th class="px-6 py-4.5">Formato</th>
                    <th class="px-6 py-4.5">Inicio</th>
                    <th class="px-6 py-4.5">Estado</th>
                    <th class="px-6 py-4.5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($tournaments as $tournament)
                <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.tournaments.show', $tournament) }}'">
                    <td class="px-6 py-4 font-bold text-gray-800 group-hover:text-green-700 transition-colors">
                        {{ $tournament->name }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-medium">
                        {{ $tournament->edition }}
                    </td>
                    <td class="px-6 py-4 text-gray-500 font-medium">
                        {{ $tournament->format === 'groups_knockout' ? 'Grupos + Eliminatoria' : ucfirst($tournament->format) }}
                    </td>
                    <td class="px-6 py-4 text-gray-400 font-mono text-xs font-semibold">
                        {{ $tournament->starts_at->format('d/m/Y') }}
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
                    <td class="px-6 py-4" onclick="event.stopPropagation()">
                        <div class="flex items-center justify-center gap-3 font-semibold text-xs">
                            <a href="{{ route('admin.tournaments.show', $tournament) }}"
                               class="text-green-600 hover:text-green-800 hover:underline transition-colors">
                                Ver
                            </a>
                            <span class="text-gray-200">|</span>
                            <a href="{{ route('admin.tournaments.edit', $tournament) }}"
                               class="text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                Editar
                            </a>
                            <span class="text-gray-200">|</span>
                            <button type="button"
                                    onclick="confirmDelete('{{ route('admin.tournaments.destroy', $tournament) }}', '¿Eliminar el torneo {{ $tournament->name }}? Esta acción eliminará todos sus partidos y grupos.')"
                                    class="text-red-500 hover:text-red-700 hover:underline transition-colors focus:outline-none">
                                Eliminar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-gray-400 bg-slate-50/30">
                        <div class="flex flex-col items-center justify-center gap-2">
                            <span class="text-2xl">📁</span>
                            <span>No hay torneos registrados.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tournaments->hasPages())
    <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100">
        {{ $tournaments->links() }}
    </div>
    @endif
</div>
@endsection