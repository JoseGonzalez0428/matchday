<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-900 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-6">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="text-7xl mb-4">⚽</div>
            <h1 class="text-4xl font-bold text-white tracking-wide">MatchDay</h1>
            <p class="text-green-300 mt-2 text-sm">Sistema de Gestión de Torneos</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Iniciar sesión</h2>

            {{-- Errores --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Correo electrónico
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="admin@matchday.test"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  @error('email') border-red-400 @enderror">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Contraseña
                    </label>
                    <input type="password" name="password" required
                           placeholder="••••••••"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent
                                  @error('password') border-red-400 @enderror">
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                        <input type="checkbox" name="remember"
                               class="rounded border-gray-300 text-green-600 focus:ring-green-500">
                        Recordarme
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-sm text-green-700 hover:underline">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit"
                        class="w-full bg-green-700 text-white py-3 rounded-lg font-medium
                               hover:bg-green-800 transition-colors duration-200">
                    Entrar
                </button>
            </form>
        </div>

        {{-- Footer --}}
        <p class="text-center text-green-400 text-xs mt-6">
            Copa MatchDay 2026 · Desarrollo Web Avanzado
        </p>

    </div>

</body>
</html>