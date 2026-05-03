@extends('layouts.admin')

@section('title', 'Editar Equipo')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-green-800">👕 Editar Equipo</h1>
</div>

<div class="bg-white rounded-xl shadow p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.teams.update', $team) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del equipo</label>
            <input type="text" name="name" value="{{ old('name', $team->name) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                   @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">País</label>
            <input type="text" name="country" value="{{ old('country', $team->country) }}"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Capitán</label>
            <select name="captain_id"
                    class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <option value="">Sin capitán asignado</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}"
                        {{ old('captain_id', $team->captain_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Escudo del equipo</label>
            @if($team->shield_url)
                <div class="mb-2">
                    <img src="{{ Storage::url($team->shield_url) }}"
                         class="w-16 h-16 rounded-full object-cover border">
                    <p class="text-xs text-gray-400 mt-1">Escudo actual. Sube uno nuevo para reemplazarlo.</p>
                </div>
            @endif
            <input type="file" name="shield" accept="image/*"
                   class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                   @error('shield') border-red-500 @enderror">
            <p class="text-xs text-gray-400 mt-1">Máximo 2MB. Formatos: JPG, PNG, SVG.</p>
            @error('shield')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
                Guardar cambios
            </button>
            <a href="{{ route('admin.teams.show', $team) }}"
               class="px-6 py-2 rounded-lg border hover:bg-gray-50 text-gray-600">
                Cancelar
            </a>
        </div>
    </form>
</div>
@endsection