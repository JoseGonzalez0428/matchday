@extends('layouts.admin')

@section('title', 'Capitanes')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-green-800">👤 Capitanes</h1>
    <a href="{{ route('admin.users.create') }}"
       class="bg-green-700 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-800">
        + Nuevo capitán
    </a>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="overflow-x-auto"><table class="w-full text-sm">
        <thead class="bg-green-700 text-white">
            <tr>
                <th class="text-left px-4 py-3">Nombre</th>
                <th class="text-left px-4 py-3">Email</th>
                <th class="text-left px-4 py-3">Equipo</th>
                <th class="text-left px-4 py-3">Registro</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($captains as $captain)
            <tr class="border-t hover:bg-gray-50">
                <td class="px-4 py-3 font-medium">{{ $captain->name }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $captain->email }}</td>
                <td class="px-4 py-3">
                    @if($captain->team)
                        <a href="{{ route('admin.teams.show', $captain->team) }}"
                           class="text-green-700 hover:underline">
                            {{ $captain->team->name }}
                        </a>
                    @else
                        <span class="text-gray-400 text-xs">Sin equipo</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500">{{ $captain->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3 flex gap-2 justify-center">
                    <a href="{{ route('admin.users.edit', $captain) }}"
                       class="text-blue-600 hover:underline">Editar</a>
                    <button type="button"
                            onclick="confirmDelete('{{ route('admin.users.destroy', $captain) }}', '¿Eliminar este capitán? Esta acción no se puede deshacer.')"
                            class="text-red-600 hover:underline">
                        Eliminar
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                    No hay capitanes registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table></div>
    <div class="px-4 py-3 border-t">
        {{ $captains->links() }}
    </div>
</div>
@endsection