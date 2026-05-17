<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">

    {{-- Navbar --}}
    <nav class="bg-green-800 text-white shadow">
        <div class="px-6 py-4 flex items-center justify-between">
            {{-- Logo --}}
            <div class="flex items-center gap-3">
                <span class="text-2xl">⚽</span>
                <span class="font-bold text-xl tracking-wide">MatchDay</span>
                <span class="text-green-300 text-sm ml-2">Capitán</span>
            </div>

            {{-- Links desktop --}}
            <div class="hidden md:flex items-center gap-6 text-sm">
                <a href="{{ route('captain.dashboard') }}" class="hover:text-green-300">Dashboard</a>
                <a href="{{ route('captain.team.show') }}" class="hover:text-green-300">Mi Equipo</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-300">Cerrar sesión</button>
                </form>
            </div>

            {{-- Botón hamburguesa --}}
            <button class="md:hidden flex flex-col gap-1.5 p-2"
                    onclick="document.getElementById('captain-mobile-menu').classList.toggle('hidden')">
                <span class="block w-6 h-0.5 bg-white"></span>
                <span class="block w-6 h-0.5 bg-white"></span>
                <span class="block w-6 h-0.5 bg-white"></span>
            </button>
        </div>

        {{-- Menú móvil --}}
        <div id="captain-mobile-menu" class="hidden md:hidden border-t border-green-700">
            <div class="flex flex-col px-6 py-3 gap-3 text-sm">
                <a href="{{ route('captain.dashboard') }}" class="hover:text-green-300 py-1">Dashboard</a>
                <a href="{{ route('captain.team.show') }}" class="hover:text-green-300 py-1">Mi Equipo</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-red-300 py-1 text-left">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Mensajes flash --}}
    <div class="max-w-7xl mx-auto px-6 mt-4">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Contenido --}}
    <main class="max-w-7xl mx-auto px-6 py-6">
        @yield('content')
    </main>

</body>
</html>