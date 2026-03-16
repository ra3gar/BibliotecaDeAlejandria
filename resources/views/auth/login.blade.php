<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — Biblioteca de Alejandría</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative"
      style="background-image: url('{{ asset('images/Fondo.png') }}'); background-size: cover; background-position: center; background-attachment: fixed;">

    {{-- Capa oscura suave para legibilidad --}}
    <div class="absolute inset-0" style="background-color: rgba(0,0,0,0.35);"></div>

<div class="w-full max-w-md lib-animate relative z-10">

    {{-- Logo --}}
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center rounded-full mb-4 drop-shadow-2xl"
             style="width: 192px; height: 192px; padding: 1px; border: 1px solid #C9A878;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Biblioteca"
                 style="width: 190px; height: 190px; object-fit: contain;">
        </div>
        <h1 class="text-3xl font-serif font-semibold text-parchment-100">Biblioteca de Alejandría</h1>
        <p class="text-sepia-400 text-sm mt-2">Ingresa tus credenciales para continuar</p>
    </div>

    {{-- Card --}}
    <div class="rounded-2xl shadow-2xl border border-parchment-300/60 p-8 backdrop-blur-sm"
         style="background-color: rgba(253, 248, 235, 0.92)">

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
                <label for="email" class="block text-sm font-medium text-sepia-600 mb-1">
                    Correo electrónico
                </label>
                <input id="email"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       required
                       autofocus
                       class="w-full px-4 py-2.5 rounded-lg border text-mahogany-900 bg-parchment-50 text-sm transition
                              focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent
                              {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-medium text-sepia-600 mb-1">
                    Contraseña
                </label>
                <input id="password"
                       type="password"
                       name="password"
                       required
                       class="w-full px-4 py-2.5 rounded-lg border border-parchment-400 text-mahogany-900 bg-parchment-50 text-sm transition
                              focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent">
            </div>

            {{-- Remember --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-sepia-500">
                    <input type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-parchment-400 text-gold-500 focus:ring-gold-500">
                    Recordarme
                </label>
            </div>

            {{-- Submit --}}
            <button type="submit"
                    class="w-full bg-gold-500 hover:bg-gold-600 text-mahogany-900 font-semibold py-2.5 px-4 rounded-lg
                           transition-all duration-200 hover:shadow-md active:scale-[0.98] text-sm tracking-wide">
                Iniciar sesión
            </button>
        </form>
    </div>

    <p class="text-center text-sepia-500 text-xs mt-6 font-serif">
        Universidad UPED &mdash; Programación Aplicada 1
    </p>
</div>

</body>
</html>
