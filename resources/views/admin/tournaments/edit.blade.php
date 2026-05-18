@extends('layouts.admin')

@section('title', 'Editar Torneo')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-green-800">🏆 Editar Torneo</h1>
    <a href="{{ route('admin.tournaments.show', $tournament) }}"
       class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
        ← Volver
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.tournaments.update', $tournament) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $tournament->name) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Edición (año)</label>
                    <input type="number" name="edition" value="{{ old('edition', $tournament->edition) }}"
                           min="1900" max="2100"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('edition') border-red-500 @enderror">
                    @error('edition')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Formato</label>
                    <select name="format"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="groups_knockout" {{ old('format', $tournament->format) === 'groups_knockout' ? 'selected' : '' }}>
                            Grupos + Eliminatoria
                        </option>
                        <option value="knockout" {{ old('format', $tournament->format) === 'knockout' ? 'selected' : '' }}>
                            Eliminatoria directa
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="status"
                            class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="draft" {{ old('status', $tournament->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                        <option value="active" {{ old('status', $tournament->status) === 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="finished" {{ old('status', $tournament->status) === 'finished' ? 'selected' : '' }}>Finalizado</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio</label>
                    <input type="date" name="starts_at"
                           value="{{ old('starts_at', $tournament->starts_at->format('Y-m-d')) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('starts_at') border-red-500 @enderror">
                    @error('starts_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de cierre <span class="text-gray-400">(opcional)</span></label>
                    <input type="date" name="ends_at"
                           value="{{ old('ends_at', $tournament->ends_at ? $tournament->ends_at->format('Y-m-d') : '') }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('ends_at') border-red-500 @enderror">
                    @error('ends_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.tournaments.show', $tournament) }}"
                   class="px-6 py-2 rounded-lg border hover:bg-gray-50 text-gray-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        <div class="bg-gray-50 border rounded-xl p-5">
            <h3 class="font-bold text-gray-700 mb-3">📊 Estado actual</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Estado</span>
                    <span class="font-bold">{{ \App\Helpers\StatusHelper::tournament($tournament->status) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Grupos</span>
                    <span class="font-bold">{{ $tournament->groups()->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Partidos</span>
                    <span class="font-bold">{{ $tournament->matches()->count() }}</span>
                </div>
            </div>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5">
            <h3 class="font-bold text-yellow-700 mb-2">⚠️ Precaución</h3>
            <p class="text-sm text-yellow-700">Cambiar el formato después de generar el fixture puede causar inconsistencias.</p>
        </div>
    </div>

</div>
@endsection