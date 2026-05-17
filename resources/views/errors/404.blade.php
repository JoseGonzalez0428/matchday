<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — Página no encontrada</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-900 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md px-6 text-center">

        {{-- Logo --}}
        <div class="text-8xl mb-6">⚽</div>

        {{-- Error --}}
        <h1 class="text-9xl font-bold text-green-700 mb-2">404</h1>
        <h2 class="text-2xl font-bold text-white mb-4">¡Fuera de juego!</h2>
        <p class="text-green-300 mb-8">
            La página que buscas no existe o fue movida.<br>
            Parece que el balón salió del campo.
        </p>

        {{-- Botones --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            @auth
                @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.dashboard') }}"
                       class="bg-white text-green-800 px-6 py-3 rounded-xl font-bold hover:bg-green-100 transition-colors">
                        ← Volver al Dashboard
                    </a>
                @else
                    <a href="{{ route('captain.dashboard') }}"
                       class="bg-white text-green-800 px-6 py-3 rounded-xl font-bold hover:bg-green-100 transition-colors">
                        ← Volver al Dashboard
                    </a>
                @endif
            @else
                <a href="{{ route('login') }}"
                   class="bg-white text-green-800 px-6 py-3 rounded-xl font-bold hover:bg-green-100 transition-colors">
                    ← Iniciar sesión
                </a>
            @endauth
        </div>

        <p class="text-green-500 text-xs mt-8">
            MatchDay · Sistema de Gestión de Torneos · UASLP 2024-2025
        </p>
    </div>

</body>
</html>