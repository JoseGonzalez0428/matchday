@extends('layouts.admin')

@section('title', 'Nuevo Jugador')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">👤</span> Nuevo Jugador
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">{{ $team->name }}</p>
        </div>
        <a href="{{ route('admin.teams.players.index', $team) }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
            ← Volver a la plantilla
        </a>
    </div>

    {{-- Tarjeta del Formulario --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.teams.players.store', $team) }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nombre completo</label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('name') border-red-500 ring-2 ring-red-100 @enderror">
                @error('name')
                    <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Número de dorsal</label>
                <input type="number" name="dorsal" value="{{ old('dorsal') }}" min="1" max="99"
                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('dorsal') border-red-500 ring-2 ring-red-100 @enderror">
                @error('dorsal')
                    <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Posición</label>
                <select name="position"
                        class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all shadow-sm @error('position') border-red-500 ring-2 ring-red-100 @enderror">
                    <option value="">Selecciona una posición</option>
                    <option value="GK"  {{ old('position') === 'GK'  ? 'selected' : '' }}>Portero (GK)</option>
                    <option value="DEF" {{ old('position') === 'DEF' ? 'selected' : '' }}>Defensa (DEF)</option>
                    <option value="MID" {{ old('position') === 'MID' ? 'selected' : '' }}>Mediocampista (MID)</option>
                    <option value="FWD" {{ old('position') === 'FWD' ? 'selected' : '' }}>Delantero (FWD)</option>
                </select>
                @error('position')
                    <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nacionalidad (opcional)</label>
                <input type="text" name="nationality" value="{{ old('nationality') }}"
                       class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm">
            </div>

            <div class="flex flex-col sm:flex-row gap-3 border-t border-gray-100 pt-5">
                <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                    Agregar jugador
                </button>
                <a href="{{ route('admin.teams.players.index', $team) }}"
                   class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm text-center transition-all">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection