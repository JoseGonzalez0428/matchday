@extends('layouts.admin')

@section('title', 'Torneos')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-green-800">🏆 Torneos</h1>
    <a href="{{ route('admin.tournaments.create') }}"
       class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800">
        + Nuevo torneo
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="text-left px-4 py-3">Nombre</th>
                <th class="text-left px-4 py-3">Edición</th>
                <th class="text-left px-4 py-3">Formato</th>
                <th class="text-left px-4 py-3">Estado</th>
                <th class="text-left px-4 py-3">Inicio</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tournaments as $tournament)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $tournament->name }}</td>
                <td class="px-4 py-3">{{ $tournament->edition }}</td>
                <td class="px-4 py-3">{{ $tournament->format }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded-full text-xs font-medium
                        {{ $tournament->status === 'active' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $tournament->status === 'draft' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $tournament->status === 'finished' ? 'bg-gray-100 text-gray-600' : '' }}">
                        {{ \App\Helpers\StatusHelper::tournament($tournament->status) }}
                    </span>
                </td>
                <td class="px-4 py-3">{{ $tournament->starts_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3 flex gap-2 justify-center">
                    <a href="{{ route('admin.tournaments.show', $tournament) }}"
                       class="text-green-700 hover:underline">Ver</a>
                    <a href="{{ route('admin.tournaments.edit', $tournament) }}"
                       class="text-blue-600 hover:underline">Editar</a>
                    <button type="button"
                            onclick="confirmDelete('{{ route('admin.tournaments.destroy', $tournament) }}', '¿Eliminar este torneo? Esta acción no se puede deshacer.')"
                            class="text-red-600 hover:underline">
                        Eliminar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-400">
                    No hay torneos registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table></div>

    <div class="px-4 py-3 border-t">
        {{ $tournaments->links() }}
    </div>
</div>
@endsection