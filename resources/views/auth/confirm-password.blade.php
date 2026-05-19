<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — Confirmar contraseña</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-950 via-green-900 to-emerald-950 min-h-screen flex items-center justify-center antialiased px-4">

    <div class="w-full max-w-md my-8">

        {{-- Logo e Identidad Visual --}}
        <div class="text-center mb-6">
            <div class="text-6xl drop-shadow-md">⚽</div>
            <h1 class="text-3xl font-black text-white tracking-tight mt-3">MatchDay</h1>
            <p class="text-green-300/80 mt-1 text-xs font-semibold uppercase tracking-wider">Sistema de Gestión de Torneos</p>
        </div>

        {{-- Contenedor de Formulario --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-2xl p-6 md:p-8">
            <div class="w-12 h-12 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center text-xl mx-auto mb-3">🛡️</div>
            <h2 class="text-xl font-black text-gray-800 mb-2 text-center">Confirmación de Seguridad</h2>
            <p class="text-xs text-gray-400 font-medium text-center mb-6 leading-relaxed">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>

            {{-- Alerta de Errores Operacionales --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-xs font-medium flex items-center gap-2">
                    <span>❌</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Contraseña</label>
                    <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm">
                </div>

                <div class="flex flex-col sm:flex-row gap-2.5 pt-2 border-t border-gray-100">
                    <button type="submit" class="flex-1 bg-green-700 hover:bg-green-800 text-white font-bold py-2.5 rounded-xl text-sm shadow-sm transition-all text-center">
                        {{ __('Confirm') }}
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="flex-1 border border-gray-200 text-gray-500 py-2.5 rounded-xl text-xs font-bold hover:bg-slate-50 transition-all text-center inline-flex items-center justify-center">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        {{-- Footer Institucional --}}
        <p class="text-center text-green-400/60 font-medium text-[10px] uppercase tracking-widest mt-6">
            Copa MatchDay 2026 • Desarrollo Web Avanzado
        </p>

    </div>

</body>
</html>