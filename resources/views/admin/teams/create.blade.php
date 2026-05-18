@extends('layouts.admin')

@section('title', 'Nuevo Equipo')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-green-800">👕 Nuevo Equipo</h1>
    <a href="{{ route('admin.teams.index') }}"
       class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
        ← Volver
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Formulario --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.teams.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del equipo</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Ej. Club América"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">País</label>
                    <input type="text" name="country" value="{{ old('country') }}"
                           placeholder="Ej. México"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Capitán</label>
                <select name="captain_id"
                        class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">Sin capitán asignado</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('captain_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Escudo del equipo</label>
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
                    Crear equipo
                </button>
                <a href="{{ route('admin.teams.index') }}"
                   class="px-6 py-2 rounded-lg border hover:bg-gray-50 text-gray-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- Panel informativo --}}
    <div class="space-y-4">
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <h3 class="font-bold text-green-700 mb-3">📋 Pasos siguientes</h3>
            <ol class="space-y-2 text-sm text-green-800">
                <li class="flex gap-2"><span class="font-bold">1.</span> Crear el equipo</li>
                <li class="flex gap-2"><span class="font-bold">2.</span> Agregar jugadores al equipo</li>
                <li class="flex gap-2"><span class="font-bold">3.</span> Asignarlo a un grupo de torneo</li>
            </ol>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
            <h3 class="font-bold text-blue-700 mb-2">💡 Consejos</h3>
            <div class="space-y-2 text-sm text-blue-800">
                <p>El capitán debe estar registrado previamente en el sistema.</p>
                <p>El escudo es opcional pero mejora la presentación visual del torneo.</p>
            </div>
        </div>
    </div>

</div>
@endsection