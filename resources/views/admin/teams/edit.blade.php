@extends('layouts.admin')

@section('title', 'Editar Equipo')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">👕</span> Editar Equipo
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                Modifica los parámetros generales y la identidad visual del equipo.
            </p>
        </div>
        <a href="{{ route('admin.teams.show', $team) }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
            ← Volver
        </a>
    </div>

    {{-- Grid de Contenido --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Formulario --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.teams.update', $team) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nombre del equipo</label>
                        <input type="text" name="name" value="{{ old('name', $team->name) }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('name') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('name')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">País</label>
                        <input type="text" name="country" value="{{ old('country', $team->country) }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm">
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Capitán</label>
                    <select name="captain_id"
                            class="w-full bg-white border border-gray-200 rounded-xl px-3 py-2.5 text-sm font-medium focus:ring-2 focus:ring-green-500 focus:border-green-500 focus:outline-none transition-all shadow-sm">
                        <option value="">Sin capitán asignado</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('captain_id', $team->captain_id) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Escudo del equipo</label>
                    @if($team->shield_url)
                        <div class="flex items-center gap-3 mb-3 p-3.5 bg-slate-50 border border-gray-100 rounded-xl shadow-inner">
                            <img src="{{ Storage::url($team->shield_url) }}" class="w-12 h-12 rounded-full object-cover border bg-white p-0.5 shadow-sm">
                            <p class="text-xs text-gray-400 font-medium">Escudo actual. Sube uno nuevo para reemplazarlo.</p>
                        </div>
                    @endif
                    <input type="file" name="shield" accept="image/*"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-medium file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all shadow-sm @error('shield') border-red-500 ring-2 ring-red-100 @enderror">
                    <p class="text-[11px] text-gray-400 font-medium mt-1.5">Máximo 2MB. Formatos: JPG, PNG, SVG.</p>
                    @error('shield')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 border-t border-gray-100 pt-5">
                    <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                        Guardar cambios
                    </button>
                    <a href="{{ route('admin.teams.show', $team) }}"
                       class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm text-center transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Lateral de Información Adicional --}}
        <div class="space-y-4">
            <div class="bg-slate-50/60 border border-gray-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-gray-800 text-sm tracking-tight mb-3 flex items-center gap-1.5">
                    <span>📊</span> Info del equipo
                </h3>
                <div class="space-y-2.5 text-xs font-medium">
                    <div class="flex justify-between items-center bg-white border px-3 py-2 rounded-xl">
                        <span class="text-gray-400">Jugadores</span>
                        <span class="font-bold text-gray-700">{{ $team->players()->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white border px-3 py-2 rounded-xl">
                        <span class="text-gray-400">Partidos jugados</span>
                        <span class="font-bold text-gray-700">
                            {{ \App\Models\TournamentMatch::where('status', 'finished')
                                ->where(fn($q) => $q->where('home_team_id', $team->id)->orWhere('away_team_id', $team->id))
                                ->count() }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-5 shadow-sm flex items-start gap-3">
                <span class="text-lg pt-0.5">💡</span>
                <div>
                    <h3 class="font-bold text-blue-900 text-xs uppercase tracking-wider mb-1">Tip</h3>
                    <p class="text-xs text-blue-700 leading-relaxed font-medium">
                        Para agregar o editar jugadores ve a la vista del equipo.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection