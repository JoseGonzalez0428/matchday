@extends('layouts.admin')

@section('title', 'Editar Torneo')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">🏆</span> Editar Torneo
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                Modifica los parámetros generales del torneo actual.
            </p>
        </div>
        <a href="{{ route('admin.tournaments.show', $tournament) }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
            ← Volver
        </a>
    </div>

    {{-- Grid de Contenido --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Formulario Principal --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.tournaments.update', $tournament) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $tournament->name) }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('name') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('name')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Edición (año)</label>
                        <input type="number" name="edition" value="{{ old('edition', $tournament->edition) }}" min="1900" max="2100"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('edition') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('edition')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Formato</label>
                        <select name="format"
                                class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all shadow-sm">
                            <option value="groups_knockout" {{ old('format', $tournament->format) === 'groups_knockout' ? 'selected' : '' }}>
                                Grupos + Eliminatoria
                            </option>
                            <option value="knockout" {{ old('format', $tournament->format) === 'knockout' ? 'selected' : '' }}>
                                Eliminatoria directa
                            </option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Estado</label>
                        <select name="status"
                                class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all shadow-sm">
                            <option value="draft" {{ old('status', $tournament->status) === 'draft' ? 'selected' : '' }}>Borrador</option>
                            <option value="active" {{ old('status', $tournament->status) === 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="finished" {{ old('status', $tournament->status) === 'finished' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Fecha de inicio</label>
                        <input type="date" name="starts_at" value="{{ old('starts_at', $tournament->starts_at->format('Y-m-d')) }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('starts_at') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('starts_at')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Fecha de cierre <span class="text-gray-400 lowercase font-normal">(opcional)</span></label>
                        <input type="date" name="ends_at" value="{{ old('ends_at', $tournament->ends_at ? $tournament->ends_at->format('Y-m-d') : '') }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('ends_at') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('ends_at')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 border-t border-gray-100 pt-5">
                    <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                        Guardar cambios
                    </button>
                    <a href="{{ route('admin.tournaments.show', $tournament) }}"
                       class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm text-center transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Lateral de Información Adicional --}}
        <div class="space-y-4">
            {{-- Módulo Estado Actual --}}
            <div class="bg-slate-50/60 border border-gray-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-gray-800 text-sm tracking-tight mb-3 flex items-center gap-1.5">
                    <span>📊</span> Estado actual
                </h3>
                <div class="space-y-2.5 text-xs font-medium">
                    <div class="flex justify-between items-center bg-white border px-3 py-2 rounded-xl">
                        <span class="text-gray-400">Estado</span>
                        <span class="font-bold text-gray-700">{{ \App\Helpers\StatusHelper::tournament($tournament->status) }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white border px-3 py-2 rounded-xl">
                        <span class="text-gray-400">Grupos</span>
                        <span class="font-bold text-gray-700">{{ $tournament->groups()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white border px-3 py-2 rounded-xl">
                        <span class="text-gray-400">Partidos</span>
                        <span class="font-bold text-gray-700">{{ $tournament->matches()->count() }}</span>
                    </div>
                </div>
            </div>

            {{-- Alerta Precaución --}}
            <div class="bg-amber-50/60 border border-amber-100 rounded-2xl p-5 shadow-sm flex items-start gap-3">
                <span class="text-lg pt-0.5">⚠️</span>
                <div>
                    <h3 class="font-bold text-amber-900 text-xs uppercase tracking-wider mb-1">Precaución</h3>
                    <p class="text-xs text-amber-700 leading-relaxed font-medium">
                        Cambiar el formato después de generar el fixture puede causar inconsistencias.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection