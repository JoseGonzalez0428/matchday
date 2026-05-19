<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay Admin — @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-50 font-sans antialiased text-gray-800">

    {{-- Navbar Superior --}}
    <nav class="bg-gradient-to-r from-green-900 to-green-800 text-white shadow-md sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-6 py-3.5 flex items-center justify-between">
            {{-- Logo e Identidad --}}
            <div class="flex items-center gap-2.5">
                <span class="text-2xl drop-shadow-sm">⚽</span>
                <div class="flex items-baseline gap-1">
                    <span class="font-black text-xl tracking-tight">MatchDay</span>
                    <span class="text-xs font-bold uppercase tracking-wider text-green-300 bg-green-950/40 px-2 py-0.5 rounded border border-green-700/30">Admin</span>
                </div>
            </div>

            {{-- Navegación Escritorio --}}
            <div class="hidden md:flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2 rounded-xl transition-all hover:bg-white/10 {{ request()->routeIs('admin.dashboard') ? 'bg-white/10 text-green-200' : 'text-white/90' }}">Dashboard</a>
                <a href="{{ route('admin.tournaments.index') }}" class="px-3 py-2 rounded-xl transition-all hover:bg-white/10 {{ request()->routeIs('admin.tournaments.*') ? 'bg-white/10 text-green-200' : 'text-white/90' }}">Torneos</a>
                <a href="{{ route('admin.teams.index') }}" class="px-3 py-2 rounded-xl transition-all hover:bg-white/10 {{ request()->routeIs('admin.teams.*') ? 'bg-white/10 text-green-200' : 'text-white/90' }}">Equipos</a>
                <a href="{{ route('admin.matches.index') }}" class="px-3 py-2 rounded-xl transition-all hover:bg-white/10 {{ request()->routeIs('admin.matches.*') ? 'bg-white/10 text-green-200' : 'text-white/90' }}">Partidos</a>
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2 rounded-xl transition-all hover:bg-white/10 {{ request()->routeIs('admin.users.*') ? 'bg-white/10 text-green-200' : 'text-white/90' }}">Capitanes</a>
                
                <span class="h-4 w-px bg-green-700/50 mx-2"></span>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-3 py-2 rounded-xl text-rose-300 hover:text-white hover:bg-rose-600/20 transition-all cursor-pointer">Cerrar sesión</button>
                </form>
            </div>

            {{-- Botón Hamburguesa Móvil --}}
            <button id="menu-btn" type="button" class="md:hidden flex flex-col gap-1.5 p-2 rounded-xl hover:bg-white/10 transition-colors focus:outline-none"
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
                <span class="block w-5 h-0.5 bg-white rounded-full"></span>
                <span class="block w-5 h-0.5 bg-white rounded-full"></span>
                <span class="block w-5 h-0.5 bg-white rounded-full"></span>
            </button>
        </div>

        {{-- Menú Móvil Desplegable --}}
        <div id="mobile-menu" class="hidden md:hidden border-t border-green-800 bg-green-900/95 backdrop-blur-md">
            <div class="flex flex-col px-6 py-3 gap-1 text-xs font-bold uppercase tracking-wider">
                <a href="{{ route('admin.dashboard') }}" class="px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">Dashboard</a>
                <a href="{{ route('admin.tournaments.index') }}" class="px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">Torneos</a>
                <a href="{{ route('admin.teams.index') }}" class="px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">Equipos</a>
                <a href="{{ route('admin.matches.index') }}" class="px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">Partidos</a>
                <a href="{{ route('admin.users.index') }}" class="px-3 py-2.5 rounded-lg hover:bg-white/5 transition-colors">Capitanes</a>
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

    {{-- Modal Global de Confirmación de Eliminación --}}
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-all" onclick="closeDeleteModal()">
        <div class="absolute inset-0 bg-gray-950/70 backdrop-blur-xs"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full border border-gray-100 transform transition-all" onclick="event.stopPropagation()">

            <div class="text-center mb-5">
                <div class="text-4xl mb-2">🗑️</div>
                <h2 class="text-xl font-black text-gray-800 tracking-tight">¿Eliminar registro?</h2>
                <p id="delete-modal-message" class="text-gray-500 text-xs mt-1 leading-relaxed">
                    Esta acción no se puede deshacer.
                </p>
            </div>

            <div class="flex gap-2.5">
                <form id="delete-modal-form" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-sm transition-all text-center">
                        Sí, eliminar
                    </button>
                </form>
                <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 border border-gray-200 text-gray-500 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition-all text-center">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    {{-- Scripts Lógicos del Modal Global --}}
    <script>
    function confirmDelete(url, message) {
        document.getElementById('delete-modal-form').action = url;
        document.getElementById('delete-modal-message').textContent = message || 'Esta acción no se puede deshacer.';
        document.getElementById('delete-modal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
    
    // Soporte para cierre rápido con teclado
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeDeleteModal();
    });
    </script>

</body>
</html>