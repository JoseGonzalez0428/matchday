<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-gray-800">

    {{-- Navbar Superior Capitán --}}
    <nav class="bg-gradient-to-r from-green-900 to-green-800 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-3.5 flex items-center justify-between">
            {{-- Logo e Identidad --}}
            <div class="flex items-center gap-2.5">
                <span class="text-2xl drop-shadow-sm">⚽</span>
                <div class="flex items-baseline gap-1">
                    <span class="font-black text-xl tracking-tight">MatchDay</span>
                    <span class="text-xs font-bold uppercase tracking-wider text-green-300 bg-green-950/40 px-2 py-0.5 rounded border border-green-700/30">Capitán</span>
                </div>
            </div>

            {{-- Navegación Escritorio --}}
            <div class="hidden md:flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                <a href="{{ route('captain.dashboard') }}" class="px-3 py-2 rounded-xl transition-all hover:bg-white/10 {{ request()->routeIs('captain.dashboard') ? 'bg-white/10 text-green-200' : 'text-white/90' }}">Dashboard</a>
                <a href="{{ route('captain.team.show') }}" class="px-3 py-2 rounded-xl transition-all hover:bg-white/10 {{ request()->routeIs('captain.team.*') ? 'bg-white/10 text-green-200' : 'text-white/90' }}">Mi Equipo</a>
                
                <span class="h-4 w-px bg-green-700/50 mx-2"></span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-xl text-rose-300 hover:text-white hover:bg-rose-600/20 transition-all cursor-pointer">Cerrar sesión</button>
                </form>
            </div>

            {{-- Botón Hamburguesa Móvil --}}
            <button type="button" class="md:hidden flex flex-col gap-1.5 p-2 rounded-xl hover:bg-white/10 transition-colors focus:outline-none"
                    onclick="document.getElementById('captain-mobile-menu').classList.toggle('hidden')">
                <span class="block w-5 h-0.5 bg-white rounded-full"></span>
                <span class="block w-5 h-0.5 bg-white rounded-full"></span>
                <span class="block w-5 h-0.5 bg-white rounded-full"></span>
            </button>
        </div>

        {{-- Menú Móvil Desplegable --}}
        <div id="captain-mobile-menu" class="hidden md:hidden border-t border-green-800 bg-green-900/95 backdrop-blur-md">
            <div class="flex flex-col px-6 py-3 gap-1 text-xs font-bold uppercase tracking-wider">
                <a href="{{ route('captain.dashboard') }}" class="px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">Dashboard</a>
                <a href="{{ route('captain.team.show') }}" class="px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">Mi Equipo</a>
                <hr class="border-green-800 my-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2.5 text-rose-300 hover:bg-rose-600/10 rounded-lg transition-colors">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Contenedor de Alertas Globales (Mensajes Flash) --}}
    <div class="max-w-7xl mx-auto px-6 mt-4">
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-2 text-sm font-medium shadow-xs">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl flex items-center gap-2 text-sm font-medium shadow-xs">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif
    </div>

    {{-- Bloque de Inyección de Contenido Core --}}
    <main class="max-w-7xl mx-auto px-6 py-6">
        @yield('content')
    </main>

</body>
</html>