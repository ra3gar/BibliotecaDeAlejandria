<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión — Biblioteca de Alejandría</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col lg:flex-row">

    {{-- Panel izquierdo: Branding (solo visible en desktop) --}}
    <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 relative overflow-hidden flex-col items-center justify-center"
         style="background-image: url('{{ asset('images/Fondo.png') }}'); background-size: cover; background-position: center; min-height: 100vh;">

        {{-- Gradiente oscuro sobre la imagen --}}
        <div class="absolute inset-0"
             style="background: linear-gradient(160deg, rgba(13,9,6,0.90) 0%, rgba(44,32,24,0.82) 100%);"></div>

        {{-- Contenido de branding --}}
        <div class="relative z-10 flex flex-col items-center text-center px-14">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Biblioteca"
                 class="w-28 h-28 object-contain mb-6 drop-shadow-2xl">
            <div class="w-12 h-px mb-6" style="background-color: rgba(201,151,74,0.6);"></div>
            <h1 class="text-4xl font-serif font-semibold text-parchment-100 leading-tight mb-4">
                Biblioteca<br>de Alejandría
            </h1>
            <p class="text-sm leading-relaxed max-w-xs" style="color: rgba(184,149,120,0.85);">
                Sistema de gestión de préstamos y acervo bibliográfico para la comunidad universitaria.
            </p>
        </div>

        {{-- Pie del panel --}}
        <div class="absolute bottom-8 text-center w-full px-8">
            <div class="w-8 h-px mx-auto mb-3" style="background-color: rgba(201,151,74,0.3);"></div>
            <p class="text-xs font-serif tracking-wide" style="color: rgba(90,72,56,0.8);">
                Universidad UPED &mdash; Programación Aplicada 1
            </p>
        </div>
    </div>

    {{-- Panel derecho: Formulario --}}
    <div class="flex-1 flex items-center justify-center p-8 relative"
         style="background-color: #FAF6EE; min-height: 100vh;">

        {{-- Fondo solo en móvil (el panel izquierdo está oculto) --}}
        <div class="lg:hidden absolute inset-0"
             style="background-image: url('{{ asset('images/Fondo.png') }}'); background-size: cover; background-position: center;">
            <div class="absolute inset-0" style="background-color: rgba(13,9,6,0.72);"></div>
        </div>

        <div class="relative z-10 w-full max-w-sm lib-animate">

            {{-- Logo en móvil --}}
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Biblioteca"
                     class="w-24 h-24 object-contain mx-auto mb-4 drop-shadow-2xl">
                <h1 class="text-2xl font-serif font-semibold text-parchment-100">Biblioteca de Alejandría</h1>
                <p class="text-sm mt-1" style="color: rgba(184,149,120,0.85);">Sistema de gestión de acervo</p>
            </div>

            {{-- Tarjeta del formulario --}}
            <div class="rounded-2xl shadow-2xl lg:shadow-md p-8 border"
                 style="background-color: rgba(254,253,249,0.98); border-color: rgba(228,217,200,0.7);">

                {{-- Encabezado de la tarjeta --}}
                <div class="mb-7">
                    <h2 class="text-xl font-serif font-semibold text-mahogany-900">Bienvenido de vuelta</h2>
                    <p class="text-sm mt-1 text-sepia-400">Ingresa tus credenciales para acceder</p>
                </div>

                {{-- Error de autenticación --}}
                @if ($errors->any())
                    <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex gap-2.5 items-start">
                        <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
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
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input id="email"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               autofocus
                               placeholder="correo@ejemplo.com"
                               class="form-input {{ $errors->has('email') ? 'border-red-300 bg-red-50' : '' }}">
                    </div>

                    {{-- Contraseña --}}
                    <div>
                        <label for="password" class="form-label">Contraseña</label>
                        <input id="password"
                               type="password"
                               name="password"
                               required
                               placeholder="••••••••"
                               class="form-input">
                    </div>

                    {{-- Recordarme --}}
                    <div>
                        <label class="flex items-center gap-2.5 text-sm text-sepia-500">
                            <input type="checkbox" name="remember"
                                   class="w-4 h-4 rounded border-parchment-400 accent-gold-500">
                            Recordarme en este dispositivo
                        </label>
                    </div>

                    {{-- Botón de ingreso --}}
                    <button type="submit"
                            class="w-full bg-mahogany-900 hover:bg-mahogany-700 text-parchment-100 font-semibold
                                   py-3 px-4 rounded-xl transition-all duration-200 hover:shadow-lg
                                   active:scale-[0.99] text-sm tracking-wide">
                        Iniciar sesión
                    </button>
                </form>
            </div>

            {{-- Pie en móvil --}}
            <p class="lg:hidden text-center text-xs font-serif mt-5" style="color: rgba(90,72,56,0.65);">
                Universidad UPED &mdash; Programación Aplicada 1
            </p>
        </div>
    </div>

</body>
</html>
