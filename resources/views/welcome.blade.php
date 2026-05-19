<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — Sistema de Torneos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-900 via-green-950 to-emerald-950 min-h-screen font-sans antialiased text-gray-200 selection:bg-green-500 selection:text-white">

    {{-- Navbar Superior Público --}}
    <nav class="max-w-7xl mx-auto px-6 py-5 flex items-center justify-between sticky top-0 z-50 backdrop-blur-xs bg-slate-950/10">
        <div class="flex items-center gap-2.5">
            <span class="text-2xl drop-shadow-sm">⚽</span>
            <span class="text-white font-black text-xl tracking-tight">MatchDay</span>
        </div>
        <div>
            @auth
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('captain.dashboard') }}"
                   class="inline-flex items-center justify-center bg-white hover:bg-green-50 text-green-900 font-bold text-xs uppercase tracking-wider px-4 py-2 rounded-xl shadow-md transition-all transform active:scale-95">
                    Ir al Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center bg-white/5 hover:bg-white/10 text-white font-bold text-xs uppercase tracking-wider px-4 py-2 rounded-xl border border-white/10 transition-all">
                    Iniciar sesión
                </a>
            @endauth
        </div>
    </nav>

    {{-- Bloque Hero Principal --}}
    <div class="flex flex-col items-center justify-center text-center px-6 pt-16 pb-20 max-w-4xl mx-auto">
        <div class="text-7xl drop-shadow-md mb-4 animate-bounce">⚽</div>
        <h1 class="text-4xl sm:text-6xl font-black text-white mb-6 leading-tight tracking-tight">
            Gestiona tu torneo<br>
            <span class="bg-clip-text text-transparent bg-gradient-to-r from-green-400 to-emerald-300">como un profesional</span>
        </h1>
        <p class="text-green-200/80 text-base sm:text-lg max-w-2xl mb-8 leading-relaxed font-medium">
            Crea torneos, registra equipos, genera fixtures automáticos 
            y sigue la tabla de posiciones en tiempo real de manera simple y centralizada.
        </p>
        
        @auth
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.dashboard') : route('captain.dashboard') }}"
               class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-black text-sm uppercase tracking-wider px-8 py-3.5 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                Ir al Dashboard <span class="font-mono">→</span>
            </a>
        @else
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-500 hover:to-emerald-500 text-white font-black text-sm uppercase tracking-wider px-8 py-3.5 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:translate-y-0">
                Comenzar ahora <span class="font-mono">→</span>
            </a>
        @endauth
    </div>

    {{-- Bloque de Características (Features) --}}
    <div class="max-w-6xl mx-auto px-6 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            {{-- Feature 1 --}}
            <div class="bg-white/[0.03] backdrop-blur-md rounded-2xl p-6 border border-white/[0.06] shadow-xl hover:border-green-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">🏆</div>
                <h3 class="text-white font-extrabold text-lg mb-2">Torneos completos</h3>
                <p class="text-green-200/60 text-xs md:text-sm leading-relaxed font-medium">
                    Crea torneos con fase de grupos y eliminatorias. El fixture se genera automáticamente de forma óptima.
                </p>
            </div>

            {{-- Feature 2 --}}
            <div class="bg-white/[0.03] backdrop-blur-md rounded-2xl p-6 border border-white/[0.06] shadow-xl hover:border-green-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">📊</div>
                <h3 class="text-white font-extrabold text-lg mb-2">Estadísticas en tiempo real</h3>
                <p class="text-green-200/60 text-xs md:text-sm leading-relaxed font-medium">
                    Tabla de posiciones, goleadores y gráficos estadísticos avanzados actualizados de manera automática tras cada partido.
                </p>
            </div>

            {{-- Feature 3 --}}
            <div class="bg-white/[0.03] backdrop-blur-md rounded-2xl p-6 border border-white/[0.06] shadow-xl hover:border-green-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">👥</div>
                <h3 class="text-white font-extrabold text-lg mb-2">Roles diferenciados</h3>
                <p class="text-green-200/60 text-xs md:text-sm leading-relaxed font-medium">
                    Los administradores gestionan el torneo completo, mientras los capitanes siguen la evolución de su equipo en tiempo real.
                </p>
            </div>

            {{-- Feature 4 --}}
            <div class="bg-white/[0.03] backdrop-blur-md rounded-2xl p-6 border border-white/[0.06] shadow-xl hover:border-green-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">📄</div>
                <h3 class="text-white font-extrabold text-lg mb-2">Reportes PDF</h3>
                <p class="text-green-200/60 text-xs md:text-sm leading-relaxed font-medium">
                    Descarga y distribuye el fixture completo y las tablas de posiciones consolidadas en formato PDF con un solo click.
                </p>
            </div>

            {{-- Feature 5 --}}
            <div class="bg-white/[0.03] backdrop-blur-md rounded-2xl p-6 border border-white/[0.06] shadow-xl hover:border-green-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-green-500/10 border border-green-500/20 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">🔌</div>
                <h3 class="text-white font-extrabold text-lg mb-2">API RESTful</h3>
                <p class="text-green-200/60 text-xs md:text-sm leading-relaxed font-medium">
                    Accede e integra los datos del torneo de forma ágil desde cualquier aplicación externa mediante autenticación segura.
                </p>
            </div>

            {{-- Feature 6 --}}
            <div class="bg-white/[0.03] backdrop-blur-md rounded-2xl p-6 border border-white/[0.06] shadow-xl hover:border-green-500/30 transition-all group">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">🤖</div>
                <h3 class="text-white font-extrabold text-lg mb-2">Análisis con IA</h3>
                <p class="text-green-200/60 text-xs md:text-sm leading-relaxed font-medium">
                    Predicciones inteligentes y análisis estratégico para el próximo partido basados en el rendimiento real del equipo.
                </p>
            </div>

        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center pb-8 border-t border-white/[0.04] pt-6">
        <p class="text-[11px] font-bold text-green-600 uppercase tracking-widest">
            MatchDay • Desarrollo Web Avanzado • UASLP 2024-2025
        </p>
    </footer>

</body>
</html>