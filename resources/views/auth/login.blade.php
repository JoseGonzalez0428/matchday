<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-green-950 via-green-900 to-emerald-950 min-h-screen flex items-center justify-center antialiased px-4">

    <div class="w-full max-w-md my-8">

        {{-- Logo e Identidad Visual --}}
        <div class="text-center mb-6">
            <div class="text-6xl drop-shadow-md animate-fade-in">⚽</div>
            <h1 class="text-3xl font-black text-white tracking-tight mt-3">MatchDay</h1>
            <p class="text-green-300/80 mt-1 text-xs font-semibold uppercase tracking-wider">Sistema de Gestión de Torneos</p>
        </div>

        {{-- Contenedor de Formulario --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-2xl p-6 md:p-8">
            <h2 class="text-xl font-black text-gray-800 mb-6 text-center">Iniciar sesión</h2>

            {{-- Alerta de Errores Operacionales --}}
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-xs font-medium flex items-center gap-2">
                    <span>❌</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@matchday.test"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('email') border-red-500 ring-2 ring-red-100 @enderror">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Contraseña</label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm @error('password') border-red-500 ring-2 ring-red-100 @enderror">
                </div>

                <div class="flex items-center justify-between text-xs font-semibold py-1">
                    <label class="flex items-center gap-2 text-gray-600 cursor-pointer select-none">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 focus:ring-green-500 h-4 w-4">
                        Recordarme
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-green-700 hover:text-green-800 hover:underline transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-2.5 rounded-xl text-sm shadow-sm transition-all text-center mt-2">
                    Entrar
                </button>
            </form>
        </div>

        {{-- Footer Institucional --}}
        <p class="text-center text-green-400/60 font-medium text-[10px] uppercase tracking-widest mt-6">
            Copa MatchDay 2026 • Desarrollo Web Avanzado
        </p>

    </div>

</body>
</html>