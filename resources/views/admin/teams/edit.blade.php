@extends('layouts.admin')

@section('title', 'Editar Equipo')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-green-800">👕 Editar Equipo</h1>
    <a href="{{ route('admin.teams.show', $team) }}"
       class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
        ← Volver
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.teams.update', $team) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del equipo</label>
                    <input type="text" name="name" value="{{ old('name', $team->name) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('name') border-red-500 @enderror">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">País</label>
                    <input type="text" name="country" value="{{ old('country', $team->country) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
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
                    <div class="flex items-center gap-3 mb-3 p-3 bg-gray-50 rounded-lg">
                        <img src="{{ Storage::url($team->shield_url) }}"
                             class="w-12 h-12 rounded-full object-cover border">
                        <p class="text-xs text-gray-500">Escudo actual. Sube uno nuevo para reemplazarlo.</p>
                    </div>
                @endif
                <input type="file" name="shield" accept="image/*"
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                       @error('shield') border-red-500 @enderror">
                <p class="text-xs text-gray-400 mt-1">Máximo 2MB. Formatos: JPG, PNG, SVG.</p>
                @error('shield')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.teams.show', $team) }}"
                   class="px-6 py-2 rounded-lg border hover:bg-gray-50 text-gray-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    <div class="space-y-4">
        <div class="bg-gray-50 border rounded-xl p-5">
            <h3 class="font-bold text-gray-700 mb-3">📊 Info del equipo</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Jugadores</span>
                    <span class="font-bold">{{ $team->players()->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Partidos jugados</span>
                    <span class="font-bold">
                        {{ \App\Models\TournamentMatch::where('status', 'finished')
                            ->where(fn($q) => $q->where('home_team_id', $team->id)->orWhere('away_team_id', $team->id))
                            ->count() }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
            <h3 class="font-bold text-blue-700 mb-2">💡 Tip</h3>
            <p class="text-sm text-blue-700">Para agregar o editar jugadores ve a la vista del equipo.</p>
        </div>
    </div>

</div>
@endsection