<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — Sistema de Torneos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-900 min-h-screen">

    {{-- Navbar --}}
    <nav class="flex items-center justify-between px-8 py-5">
        <div class="flex items-center gap-3">
            <span class="text-3xl">⚽</span>
            <span class="text-white font-bold text-2xl tracking-wide">MatchDay</span>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('captain.dashboard') }}"
                class="bg-white text-green-800 px-5 py-2 rounded-lg text-sm font-medium hover:bg-green-50">
                    Ir al Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                class="text-green-300 hover:text-white text-sm transition-colors">
                    Iniciar sesión
                </a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <div class="flex flex-col items-center justify-center text-center px-6 pt-16 pb-24">
        <div class="text-8xl mb-6">⚽</div>
        <h1 class="text-6xl font-bold text-white mb-4 leading-tight">
            Gestiona tu torneo<br>
            <span class="text-green-300">como un profesional</span>
        </h1>
        <p class="text-green-200 text-xl max-w-2xl mb-10">
            Crea torneos, registra equipos, genera fixtures automáticos
            y sigue la tabla de posiciones en tiempo real.
        </p>
        @auth
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('captain.dashboard') }}"
            class="bg-white text-green-800 px-8 py-4 rounded-xl text-lg font-bold hover:bg-green-50 transition-colors">
                Ir al Dashboard →
            </a>
        @else
            <a href="{{ route('login') }}"
            class="bg-white text-green-800 px-8 py-4 rounded-xl text-lg font-bold hover:bg-green-50 transition-colors">
                Comenzar ahora →
            </a>
        @endauth
    </div>

    {{-- Features --}}
    <div class="max-w-6xl mx-auto px-6 pb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-green-800 bg-opacity-50 rounded-2xl p-6 border border-green-700">
                <div class="text-4xl mb-4">🏆</div>
                <h3 class="text-white font-bold text-xl mb-2">Torneos completos</h3>
                <p class="text-green-300 text-sm leading-relaxed">
                    Crea torneos con fase de grupos y eliminatorias.
                    El fixture se genera automáticamente.
                </p>
            </div>

            <div class="bg-green-800 bg-opacity-50 rounded-2xl p-6 border border-green-700">
                <div class="text-4xl mb-4">📊</div>
                <h3 class="text-white font-bold text-xl mb-2">Estadísticas en tiempo real</h3>
                <p class="text-green-300 text-sm leading-relaxed">
                    Tabla de posiciones, goleadores y gráficos
                    actualizados después de cada partido.
                </p>
            </div>

            <div class="bg-green-800 bg-opacity-50 rounded-2xl p-6 border border-green-700">
                <div class="text-4xl mb-4">👥</div>
                <h3 class="text-white font-bold text-xl mb-2">Roles diferenciados</h3>
                <p class="text-green-300 text-sm leading-relaxed">
                    Administradores gestionan el torneo.
                    Capitanes siguen a su equipo en tiempo real.
                </p>
            </div>

            <div class="bg-green-800 bg-opacity-50 rounded-2xl p-6 border border-green-700">
                <div class="text-4xl mb-4">📄</div>
                <h3 class="text-white font-bold text-xl mb-2">Reportes PDF</h3>
                <p class="text-green-300 text-sm leading-relaxed">
                    Descarga el fixture y la tabla de posiciones
                    en PDF con un solo click.
                </p>
            </div>

            <div class="bg-green-800 bg-opacity-50 rounded-2xl p-6 border border-green-700">
                <div class="text-4xl mb-4">🔌</div>
                <h3 class="text-white font-bold text-xl mb-2">API RESTful</h3>
                <p class="text-green-300 text-sm leading-relaxed">
                    Accede a los datos del torneo desde cualquier
                    aplicación externa con autenticación segura.
                </p>
            </div>

            <div class="bg-green-800 bg-opacity-50 rounded-2xl p-6 border border-green-700">
                <div class="text-4xl mb-4">🤖</div>
                <h3 class="text-white font-bold text-xl mb-2">Análisis con IA</h3>
                <p class="text-green-300 text-sm leading-relaxed">
                    Predicciones inteligentes del próximo partido
                    basadas en el rendimiento real del equipo.
                </p>
            </div>

        </div>
    </div>

    {{-- Footer --}}
    <div class="text-center pb-8">
        <p class="text-green-500 text-sm">
            MatchDay · Desarrollo Web Avanzado · UASLP 2024-2025
        </p>
    </div>

</body>
</html>