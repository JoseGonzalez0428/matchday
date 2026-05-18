@extends('layouts.admin')

@section('title', 'Nuevo Torneo')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-green-800">🏆 Nuevo Torneo</h1>
    <a href="{{ route('admin.tournaments.index') }}"
       class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
        ← Volver
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Formulario --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.tournaments.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del torneo</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Ej. Copa MatchDay 2026"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Edición (año)</label>
                    <input type="number" name="edition" value="{{ old('edition', date('Y')) }}"
                           min="1900" max="2100"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('edition') border-red-500 @enderror">
                    @error('edition')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Formato</label>
                <select name="format"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="groups_knockout" {{ old('format') === 'groups_knockout' ? 'selected' : '' }}>
                        Grupos + Eliminatoria
                    </option>
                    <option value="league" disabled>Liga (próximamente)</option>
                    <option value="knockout" {{ old('format') === 'knockout' ? 'selected' : '' }}>Eliminatoria directa</option>
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio</label>
                    <input type="date" name="starts_at" value="{{ old('starts_at') }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('starts_at') border-red-500 @enderror">
                    @error('starts_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de cierre <span class="text-gray-400">(opcional)</span></label>
                    <input type="date" name="ends_at" value="{{ old('ends_at') }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('ends_at') border-red-500 @enderror">
                    @error('ends_at')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
                    Crear torneo
                </button>
                <a href="{{ route('admin.tournaments.index') }}"
                   class="px-6 py-2 rounded-lg border hover:bg-gray-50 text-gray-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- Panel informativo --}}
    <div class="space-y-4">
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <h3 class="font-bold text-green-700 mb-3">📋 Flujo del torneo</h3>
            <ol class="space-y-2 text-sm text-green-800">
                <li class="flex gap-2"><span class="font-bold">1.</span> Crear el torneo</li>
                <li class="flex gap-2"><span class="font-bold">2.</span> Agregar grupos (mín. 2)</li>
                <li class="flex gap-2"><span class="font-bold">3.</span> Asignar equipos (mín. 3 por grupo)</li>
                <li class="flex gap-2"><span class="font-bold">4.</span> Generar fixture</li>
                <li class="flex gap-2"><span class="font-bold">5.</span> Cargar resultados</li>
                <li class="flex gap-2"><span class="font-bold">6.</span> Generar siguiente fase</li>
            </ol>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
            <h3 class="font-bold text-blue-700 mb-2">💡 Formatos disponibles</h3>
            <div class="space-y-2 text-sm text-blue-800">
                <p><strong>Grupos + Eliminatoria</strong> — Fase de grupos seguida de eliminatorias directas.</p>
                <p><strong>Liga (Próximamente)</strong> — Todos contra todos, gana quien más puntos acumule.</p>
            </div>
        </div>
    </div>

</div>
@endsection