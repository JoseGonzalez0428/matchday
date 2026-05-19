@extends('layouts.admin')

@section('title', 'Nuevo Capitán')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">👤</span> Nuevo Capitán
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                Registra un nuevo usuario con acceso de gestión de equipo.
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
            ← Volver
        </a>
    </div>

    {{-- Grid de Contenido --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Formulario --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nombre completo</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Ej. André Jardine"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('name') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="capitan@matchday.test"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('email') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Contraseña</label>
                        <input type="password" name="password" placeholder="••••••••"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('password') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 border-t border-gray-100 pt-5">
                    <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                        Crear capitán
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm text-center transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Panel informativo lateral --}}
        <div class="space-y-4">
            <div class="bg-green-50/60 border border-green-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-green-700 text-sm tracking-tight mb-3 flex items-center gap-1.5">
                    <span>📋</span> Rol de Capitán
                </h3>
                <ul class="space-y-2.5 text-xs font-medium text-green-800">
                    <li class="flex items-center gap-2">✅ Ver su equipo y jugadores</li>
                    <li class="flex items-center gap-2">✅ Ver estadísticas de partidos</li>
                    <li class="flex items-center gap-2">✅ Recibir notificaciones por correo</li>
                    <li class="flex items-center gap-2">❌ No puede modificar resultados</li>
                    <li class="flex items-center gap-2">❌ No puede editar el torneo</li>
                </ul>
            </div>

            <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-blue-700 text-sm tracking-tight mb-2 flex items-center gap-1.5">
                    <span>💡</span> Pasos siguientes
                </h3>
                <div class="text-xs font-medium text-blue-800 leading-relaxed">
                    <p>Después de crear el capitán, asígnalo a un equipo desde la sección de Equipos.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection