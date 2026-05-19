<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — Restablecer contraseña</title>
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
            <h2 class="text-xl font-black text-gray-800 mb-4 text-center">Restablecer contraseña</h2>
            <p class="text-xs text-gray-400 font-medium text-center mb-6">Establece tus nuevas credenciales de acceso seguro.</p>

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-4 text-xs font-medium flex items-center gap-2">
                    <span>❌</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $email) }}" required readonly
                           class="w-full bg-slate-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-bold text-gray-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Nueva contraseña</label>
                    <input type="password" name="password" required autofocus placeholder="••••••••"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" required placeholder="••••••••"
                           class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all shadow-sm">
                </div>

                <button type="submit" class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-2.5 rounded-xl text-sm shadow-sm transition-all text-center mt-2">
                    Restablecer contraseña
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