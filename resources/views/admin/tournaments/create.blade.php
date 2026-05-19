@extends('layouts.admin')

@section('title', 'Nuevo Torneo')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">🏆</span> Nuevo Torneo
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                Registra los parámetros iniciales para abrir un torneo.
            </p>
        </div>
        <a href="{{ route('admin.tournaments.index') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
            ← Volver
        </a>
    </div>

    {{-- Grid de Contenido --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Formulario de Creación --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.tournaments.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nombre del torneo</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej. Copa MatchDay 2026"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('name') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('name')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Edición (año)</label>
                        <input type="number" name="edition" value="{{ old('edition', date('Y')) }}" min="1900" max="2100"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('edition') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('edition')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Formato</label>
                    <select name="format"
                            class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all shadow-sm">
                        <option value="groups_knockout" {{ old('format') === 'groups_knockout' ? 'selected' : '' }}>
                            Grupos + Eliminatoria
                        </option>
                        <option value="league" disabled>Liga (próximamente)</option>
                        <option value="knockout" disabled>Eliminatoria directa (próximamente)</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Fecha de inicio</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at') }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('starts_at') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('starts_at')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Fecha de cierre <span class="text-gray-400 lowercase font-normal">(opcional)</span></label>
                        <input type="date" name="ends_at" value="{{ old('ends_at') }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('ends_at') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('ends_at')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 border-t border-gray-100 pt-5">
                    <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                        Crear torneo
                    </button>
                    <a href="{{ route('admin.tournaments.index') }}"
                       class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm text-center transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Lateral de Información Adicional --}}
        <div class="space-y-4">
            {{-- Panel Informativo Flujo --}}
            <div class="bg-green-50/60 border border-green-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-green-700 text-sm tracking-tight mb-3 flex items-center gap-1.5">
                    <span>📋</span> Flujo del torneo
                </h3>
                <ol class="space-y-2.5 text-xs font-medium text-green-800">
                    <li class="flex gap-2"><span class="font-bold text-green-600">1.</span> Crear el torneo</li>
                    <li class="flex gap-2"><span class="font-bold text-green-600">2.</span> Agregar grupos (mín. 2)</li>
                    <li class="flex gap-2"><span class="font-bold text-green-600">3.</span> Agregar equipos (mín. 3 por grupo)</li>
                    <li class="flex gap-2"><span class="font-bold text-green-600">4.</span> Generar fixture</li>
                    <li class="flex gap-2"><span class="font-bold text-green-600">5.</span> Cargar resultados</li>
                    <li class="flex gap-2"><span class="font-bold text-green-600">6.</span> Generar siguiente fase</li>
                </ol>
            </div>

            {{-- Panel Formatos Disponibles --}}
            <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-blue-700 text-sm tracking-tight mb-2 flex items-center gap-1.5">
                    <span>💡</span> Formatos disponibles
                </h3>
                <div class="space-y-3 text-xs font-medium text-blue-800/90 leading-relaxed">
                    <p><strong>Grupos + Eliminatoria</strong> — Fase de grupos seguida de eliminatorias directas.</p>
                    <p><strong>Liga (Próximamente)</strong> — Todos contra todos, gana quien más puntos acumule.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection