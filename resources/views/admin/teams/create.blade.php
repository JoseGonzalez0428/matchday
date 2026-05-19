@extends('layouts.admin')

@section('title', 'Nuevo Equipo')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">👕</span> Nuevo Equipo
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                Registra un nuevo equipo e integra los datos para la competición.
            </p>
        </div>
        <a href="{{ route('admin.teams.index') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
            ← Volver
        </a>
    </div>

    {{-- Grid de Contenido --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Formulario --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.teams.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nombre del equipo</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej. Club América"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('name') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">País</label>
                        <input type="text" name="country" value="{{ old('country') }}" placeholder="Ej. México"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Capitán</label>
                    <select name="captain_id"
                            class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all shadow-sm">
                        <option value="">Sin capitán asignado</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('captain_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Escudo del equipo</label>
                    <input type="file" name="shield" accept="image/*"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-medium file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all shadow-sm @error('shield') border-red-500 ring-2 ring-red-100 @enderror">
                    <p class="text-[11px] text-gray-400 font-medium mt-1.5">Máximo 2MB. Formatos: JPG, PNG, SVG.</p>
                    @error('shield')
                        <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 border-t border-gray-100 pt-5">
                    <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                        Crear equipo
                    </button>
                    <a href="{{ route('admin.teams.index') }}"
                       class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm text-center transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Lateral Informativo --}}
        <div class="space-y-4">
            <div class="bg-green-50/60 border border-green-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-green-700 text-sm tracking-tight mb-3 flex items-center gap-1.5">
                    <span>📋</span> Pasos siguientes
                </h3>
                <ol class="space-y-2.5 text-xs font-medium text-green-800">
                    <li class="flex gap-2"><span class="font-bold text-green-600">1.</span> Crear el equipo</li>
                    <li class="flex gap-2"><span class="font-bold text-green-600">2.</span> Agregar jugadores al equipo</li>
                    <li class="flex gap-2"><span class="font-bold text-green-600">3.</span> Asignarlo a un grupo de torneo</li>
                </ol>
            </div>

            <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-blue-700 text-sm tracking-tight mb-2 flex items-center gap-1.5">
                    <span>💡</span> Consejos
                </h3>
                <div class="text-xs font-medium text-blue-800/90 leading-relaxed space-y-2">
                    <p>El capitán debe estar registrado previamente en el sistema.</p>
                    <p>El escudo es opcional pero mejora la presentación visual del torneo.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection