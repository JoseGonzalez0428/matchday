@extends('layouts.admin')

@section('title', 'Capitanes')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6 pb-5 border-b border-gray-100">
        <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center gap-2">
            <span class="text-green-600">👤</span> Capitanes
        </h1>
        <a href="{{ route('admin.users.create') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 bg-green-700 hover:bg-green-800 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow-sm transition-all">
            ➕ Nuevo capitán
        </a>
    </div>

    {{-- Tabla Principal de Capitanes --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs md:text-sm text-left">
                <thead class="bg-slate-50 border-b border-gray-100 text-gray-500 uppercase font-bold tracking-wider text-[10px] md:text-xs">
                    <tr>
                        <th class="px-6 py-4.5">Nombre</th>
                        <th class="px-6 py-4.5">Email</th>
                        <th class="px-6 py-4.5">Equipo</th>
                        <th class="px-6 py-4.5">Registro</th>
                        <th class="px-6 py-4.5 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($captains as $captain)
                    <tr class="hover:bg-slate-50/50 transition-colors cursor-pointer group" onclick="window.location='{{ route('admin.users.edit', $captain) }}'">
                        <td class="px-6 py-4 font-bold text-gray-800 group-hover:text-green-700 transition-colors">
                            {{ $captain->name }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 font-medium font-mono text-xs">
                            {{ $captain->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap" onclick="event.stopPropagation()">
                            @if($captain->team)
                                <a href="{{ route('admin.teams.show', $captain->team) }}"
                                   class="inline-flex items-center font-bold text-green-700 hover:text-green-800 hover:underline transition-colors">
                                    {{ $captain->team->name }}
                                </a>
                            @else
                                <span class="inline-flex items-center bg-gray-50 text-gray-400 border border-gray-200 text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md">
                                    Sin equipo
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 font-mono text-xs font-semibold">
                            {{ $captain->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4" onclick="event.stopPropagation()">
                            <div class="flex items-center justify-center gap-3 font-semibold text-xs">
                                <a href="{{ route('admin.users.edit', $captain) }}"
                                   class="text-blue-600 hover:text-blue-800 hover:underline transition-colors">
                                    Editar
                                </a>
                                <span class="text-gray-200">|</span>
                                <button type="button"
                                        onclick="confirmDelete('{{ route('admin.users.destroy', $captain) }}', '¿Eliminar este capitán? Esta acción no se puede deshacer.')"
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
                                <span>No hay capitanes registrados.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($captains->hasPages())
        <div class="px-6 py-4 bg-slate-50/50 border-t border-gray-100">
            {{ $captains->links() }}
        </div>
        @endif
    </div>
</div>
@endsection