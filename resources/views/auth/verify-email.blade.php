<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MatchDay — Verificar correo electrónico</title>
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
        <div class="bg-white rounded-2xl border border-gray-100 shadow-2xl p-6 md:p-8 text-center">
            <div class="w-12 h-12 rounded-full bg-green-50 border border-green-100 flex items-center justify-center text-xl mx-auto mb-4">📧</div>
            <h2 class="text-xl font-black text-gray-800 mb-3 tracking-tight">Verificación de cuenta</h2>
            
            <p class="text-xs text-gray-500 font-medium leading-relaxed mb-4">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-4 py-2.5 rounded-xl mb-5 text-xs font-bold leading-tight">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-6 pt-4 border-t border-gray-100">
                <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto bg-green-700 hover:bg-green-800 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-sm transition-all text-center">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto text-center border border-gray-200 text-gray-400 hover:text-gray-600 hover:bg-slate-50 font-bold px-4 py-2 rounded-xl text-xs transition-all">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Footer Institucional --}}
        <p class="text-center text-green-400/60 font-medium text-[10px] uppercase tracking-widest mt-6">
            Copa MatchDay 2026 • Desarrollo Web Avanzado
        </p>

    </div>

</body>
</html>