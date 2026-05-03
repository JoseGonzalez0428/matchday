@extends('layouts.admin')

@section('title', 'Nuevo Torneo')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-green-800">🏆 Nuevo Torneo</h1>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.tournaments.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                   @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Edición (año)</label>
            <input type="number" name="edition" value="{{ old('edition', date('Y')) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                   @error('edition') border-red-500 @enderror">
            @error('edition')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Formato</label>
            <select name="format"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="groups_knockout" {{ old('format') === 'groups_knockout' ? 'selected' : '' }}>
                    Grupos + Eliminatoria
                </option>
                <option value="league" {{ old('format') === 'league' ? 'selected' : '' }}>Liga</option>
                <option value="knockout" {{ old('format') === 'knockout' ? 'selected' : '' }}>Eliminatoria directa</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de inicio</label>
            <input type="date" name="starts_at" value="{{ old('starts_at') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                   @error('starts_at') border-red-500 @enderror">
            @error('starts_at')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de cierre (opcional)</label>
            <input type="date" name="ends_at" value="{{ old('ends_at') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                   @error('ends_at') border-red-500 @enderror">
            @error('ends_at')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
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
@endsection