@extends('layouts.admin')

@section('title', 'Nuevo Jugador')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-green-800">👤 Nuevo Jugador</h1>
    <p class="text-gray-500 mt-1">{{ $team->name }}</p>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.teams.players.store', $team) }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                   @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Número de dorsal</label>
            <input type="number" name="dorsal" value="{{ old('dorsal') }}" min="1" max="99"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                   @error('dorsal') border-red-500 @enderror">
            @error('dorsal')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Posición</label>
            <select name="position"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                    @error('position') border-red-500 @enderror">
                <option value="">Selecciona una posición</option>
                <option value="GK"  {{ old('position') === 'GK'  ? 'selected' : '' }}>Portero (GK)</option>
                <option value="DEF" {{ old('position') === 'DEF' ? 'selected' : '' }}>Defensa (DEF)</option>
                <option value="MID" {{ old('position') === 'MID' ? 'selected' : '' }}>Mediocampista (MID)</option>
                <option value="FWD" {{ old('position') === 'FWD' ? 'selected' : '' }}>Delantero (FWD)</option>
            </select>
            @error('position')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nacionalidad (opcional)</label>
            <input type="text" name="nationality" value="{{ old('nationality') }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
                Agregar jugador
            </button>
            <a href="{{ route('admin.teams.players.index', $team) }}"
               class="px-6 py-2 rounded-lg border hover:bg-gray-50 text-gray-600">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection