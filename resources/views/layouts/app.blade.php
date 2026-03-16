<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca de Alejandría')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('page-styles')
</head>
<body class="bg-parchment-200 font-sans antialiased">

{{-- Navbar --}}
<nav class="bg-mahogany-950 text-parchment-200 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-24">

            {{-- Left: Logo + Links --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('catalogo') }}" class="flex items-center">
                    <div class="inline-flex items-center justify-center rounded-full shrink-0 drop-shadow-md"
                         style="width: 92px; height: 92px; padding: 1px; border: 1px solid #C9A878;">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo"
                             style="width: 90px; height: 90px; object-fit: contain;">
                    </div>
                </a>
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('catalogo') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition-all duration-200
                              {{ request()->routeIs('catalogo') ? 'text-gold-400' : 'text-parchment-400 hover:text-gold-300' }}">
                        Inicio
                    </a>
                    <a href="{{ route('catalogo') }}#categorias"
                       class="px-3 py-2 rounded-md text-sm font-medium text-parchment-400 hover:text-gold-300 transition-all duration-200">
                        Categorías
                    </a>
                    <a href="{{ route('catalogo') }}#autores"
                       class="px-3 py-2 rounded-md text-sm font-medium text-parchment-400 hover:text-gold-300 transition-all duration-200">
                        Autores
                    </a>
                </div>
            </div>

            {{-- Right: User profile --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-mahogany-800 transition-all duration-200">
                    <div class="w-8 h-8 rounded-full bg-gold-500 flex items-center justify-center text-mahogany-900 font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-parchment-200">{{ auth()->user()->full_name }}</span>
                    <svg class="w-4 h-4 text-sepia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute right-0 mt-2 w-48 bg-parchment-50 rounded-lg shadow-lg border border-parchment-300 py-1 z-50">
                    <a href="{{ route('profile') }}"
                       class="flex items-center gap-2 px-4 py-2 text-sm text-sepia-600 hover:bg-parchment-100">
                        <svg class="w-4 h-4 text-sepia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Mi perfil
                    </a>
                    <hr class="my-1 border-parchment-300">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-sepia-600 hover:bg-parchment-100">
                            <svg class="w-4 h-4 text-sepia-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</nav>

{{-- Page content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-mahogany-950 text-sepia-400 text-sm text-center py-6 mt-16">
    <p class="font-serif tracking-wide">&copy; {{ date('Y') }} Biblioteca de Alejandría</p>
    <p class="text-xs mt-1 text-sepia-500">Todos los derechos reservados.</p>
</footer>

@stack('scripts')
</body>
</html>
