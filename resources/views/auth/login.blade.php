<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — Biblioteca de Alejandría</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-500 mb-4">
            <svg class="w-9 h-9 text-slate-900" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-white">Biblioteca de Alejandría</h1>
        <p class="text-slate-400 text-sm mt-1">Ingresa tus credenciales para continuar</p>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-2xl shadow-2xl p-8">

        {{-- Error general --}}
        @if ($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-3 flex gap-2">
                <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                          clip-rule="evenodd"/>
                </svg>
                <p class="text-sm text-red-700">{{ $errors->first('email') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Correo electrónico
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       class="w-full px-4 py-2.5 rounded-lg border text-gray-900 text-sm transition
                              focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent
                              {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                    Contraseña
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       class="w-full px-4 py-2.5 rounded-lg border border-gray-300 text-gray-900 text-sm transition
                              focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            </div>

            {{-- Remember --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                    Recordarme
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold py-2.5 px-4 rounded-lg transition text-sm">
                Iniciar sesión
            </button>
        </form>
    </div>

    <p class="text-center text-slate-500 text-xs mt-6">
        Universidad UPED &mdash; Programación Aplicada 1
    </p>
</div>

</body>
</html>
