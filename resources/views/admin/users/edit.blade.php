@extends('layouts.admin')

@section('title', 'Editar Capitán')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    {{-- Encabezado Principal --}}
    <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left gap-4 pb-5 border-b border-gray-100">
        <div>
            <h1 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center justify-center sm:justify-start gap-2">
                <span class="text-green-600">👤</span> Editar Capitán
            </h1>
            <p class="text-sm font-medium text-gray-400 mt-1">
                Modifica los datos de acceso o el perfil del usuario.
            </p>
        </div>
        <a href="{{ route('admin.users.index') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 text-xs font-bold text-gray-600 hover:text-gray-800 bg-white border border-gray-200 px-4 py-2.5 rounded-xl shadow-sm transition-all">
            ← Volver
        </a>
    </div>

    {{-- Grid de Contenido --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- Formulario Principal --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nombre completo</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('name') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('name')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Correo electrónico</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('email') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('email')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">
                            Nueva contraseña 
                            <span class="text-gray-400 lowercase font-normal">(dejar vacío para no cambiar)</span>
                        </label>
                        <input type="password" name="password" placeholder="••••••••"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('password') border-red-500 ring-2 ring-red-100 @enderror">
                        @error('password')<p class="text-red-500 text-xs font-medium mt-1.5 flex items-center gap-1">❌ {{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••"
                               class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 border-t border-gray-100 pt-5">
                    <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-6 py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                        Guardar cambios
                    </button>
                    <a href="{{ route('admin.users.index') }}"
                       class="w-full sm:w-auto px-6 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 text-gray-600 font-semibold text-sm text-center transition-all">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Lateral de Información Adicional --}}
        <div class="space-y-4">
            {{-- Módulo Info del Capitán --}}
            <div class="bg-slate-50/60 border border-gray-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-gray-800 text-sm tracking-tight mb-3 flex items-center gap-1.5">
                    <span>📊</span> Info del capitán
                </h3>
                <div class="space-y-2.5 text-xs font-medium">
                    <div class="flex justify-between items-center bg-white border px-3 py-2 rounded-xl">
                        <span class="text-gray-400">Equipo</span>
                        <span class="font-bold text-gray-700">{{ $user->team->name ?? 'Sin equipo' }}</span>
                    </div>
                    <div class="flex justify-between items-center bg-white border px-3 py-2 rounded-xl">
                        <span class="text-gray-400">Registrado</span>
                        <span class="font-bold text-gray-700">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Permisos --}}
            <div class="bg-green-50/60 border border-green-100 rounded-2xl p-5 shadow-sm">
                <h3 class="font-bold text-green-700 text-sm tracking-tight mb-2 flex items-center gap-1.5">
                    <span>✅</span> Permisos
                </h3>
                <ul class="space-y-2 text-xs font-medium text-green-800">
                    <li>✅ Ver su equipo</li>
                    <li>✅ Ver estadísticas</li>
                    <li>❌ No puede editar torneos</li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection