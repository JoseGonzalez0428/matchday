<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay Admin — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100 font-sans">

    {{-- Navbar --}}
    <nav class="bg-green-800 text-white px-6 py-4 flex items-center justify-between shadow">
        <div class="flex items-center gap-3">
            <span class="text-2xl">⚽</span>
            <span class="font-bold text-xl tracking-wide">MatchDay</span>
            <span class="text-green-300 text-sm ml-2">Admin</span>
        </div>
        <div class="flex items-center gap-6 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-green-300">Dashboard</a>
            <a href="{{ route('admin.tournaments.index') }}" class="hover:text-green-300">Torneos</a>
            <a href="{{ route('admin.teams.index') }}" class="hover:text-green-300">Equipos</a>
            <a href="{{ route('admin.matches.index') }}" class="hover:text-green-300">Partidos</a>
            <a href="{{ route('admin.users.index') }}" class="hover:text-green-300">Capitanes</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:text-red-300">Cerrar sesión</button>
            </form>
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