@extends('layouts.admin')

@section('title', 'Nuevo Capitán')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-3xl font-bold text-green-800">👤 Nuevo Capitán</h1>
    <a href="{{ route('admin.users.index') }}"
       class="px-4 py-2 rounded-lg border hover:bg-gray-50 text-gray-600 text-sm">
        ← Volver
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Formulario --}}
    <div class="lg:col-span-2 bg-white rounded-xl shadow p-6">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           placeholder="Ej. André Jardine"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="capitan@matchday.test"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                    <input type="password" name="password"
                           placeholder="••••••••"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500
                           @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           placeholder="••••••••"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="bg-green-700 text-white px-6 py-2 rounded-lg hover:bg-green-800">
                    Crear capitán
                </button>
                <a href="{{ route('admin.users.index') }}"
                   class="px-6 py-2 rounded-lg border hover:bg-gray-50 text-gray-600">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- Panel informativo --}}
    <div class="space-y-4">
        <div class="bg-green-50 border border-green-200 rounded-xl p-5">
            <h3 class="font-bold text-green-700 mb-3">📋 Rol de Capitán</h3>
            <ul class="space-y-2 text-sm text-green-800">
                <li class="flex gap-2">✅ Ver su equipo y jugadores</li>
                <li class="flex gap-2">✅ Ver estadísticas de partidos</li>
                <li class="flex gap-2">✅ Recibir notificaciones por correo</li>
                <li class="flex gap-2">❌ No puede modificar resultados</li>
                <li class="flex gap-2">❌ No puede editar el torneo</li>
            </ul>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
            <h3 class="font-bold text-blue-700 mb-2">💡 Pasos siguientes</h3>
            <div class="space-y-2 text-sm text-blue-800">
                <p>Después de crear el capitán, asígnalo a un equipo desde la sección de Equipos.</p>
            </div>
        </div>
    </div>

</div>
@endsection