<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca de Alejandría')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('page-styles')
</head>
<body class="bg-parchment-100 font-sans antialiased"
      style="background-image: url('{{ asset('images/Fondo2.jpg') }}'); background-size: cover; background-attachment: fixed; background-position: center;">

{{-- Navbar --}}
<nav class="bg-mahogany-950 text-parchment-200 shadow-md border-b border-gold-700/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Left: Logo + Brand + Links --}}
            <div class="flex items-center gap-6">

                {{-- Logo + Brand name --}}
                <a href="{{ route('catalogo') }}" class="flex items-center gap-3 group">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo"
                         class="w-9 h-9 object-contain shrink-0 drop-shadow"
                         style="filter: drop-shadow(0 1px 3px rgba(0,0,0,0.4));">
                    <span class="hidden sm:block font-serif font-semibold text-parchment-100 text-base tracking-wide
                                 group-hover:text-gold-400 transition-colors duration-200">
                        Biblioteca de Alejandría
                    </span>
                </a>

                {{-- Separator --}}
                <div class="hidden md:block w-px h-5 bg-mahogany-700"></div>

                {{-- Nav links --}}
                <div class="hidden md:flex items-center h-16">
                    <a href="{{ route('catalogo') }}"
                       class="inline-flex items-center h-16 px-3 text-sm font-medium border-b-2 transition-all duration-200
                              {{ request()->routeIs('catalogo') ? 'border-gold-500 text-parchment-100' : 'border-transparent text-parchment-400 hover:text-parchment-100 hover:border-gold-500/40' }}">
                        Inicio
                    </a>
                    <a href="{{ route('catalogo') }}#categorias"
                       class="inline-flex items-center h-16 px-3 text-sm font-medium border-b-2 border-transparent
                              text-parchment-400 hover:text-parchment-100 hover:border-gold-500/40 transition-all duration-200">
                        Categorías
                    </a>
                    <a href="{{ route('catalogo') }}#autores"
                       class="inline-flex items-center h-16 px-3 text-sm font-medium border-b-2 border-transparent
                              text-parchment-400 hover:text-parchment-100 hover:border-gold-500/40 transition-all duration-200">
                        Autores
                    </a>
                </div>
            </div>

            {{-- Right: User dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-mahogany-800 transition-all duration-200">
                    <div class="w-8 h-8 rounded-full bg-gold-500 flex items-center justify-center text-mahogany-900 font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                    </div>
                    <span class="hidden sm:block text-sm font-medium text-parchment-200">
                        {{ auth()->user()->full_name }}
                    </span>
                    <svg class="w-3.5 h-3.5 text-sepia-400 transition-transform duration-200"
                         :class="open ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-52 bg-parchment-50 rounded-xl shadow-lg border border-parchment-300 py-1.5 z-50 origin-top-right">
                    <a href="{{ route('profile') }}"
                       class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-sepia-600 hover:bg-parchment-100 hover:text-mahogany-900 transition-colors duration-150">
                        <svg class="w-4 h-4 text-sepia-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Mi perfil
                    </a>
                    <div class="my-1 h-px bg-parchment-300 mx-3"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-sepia-600 hover:bg-parchment-100 hover:text-mahogany-900 transition-colors duration-150">
                            <svg class="w-4 h-4 text-sepia-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
<footer class="bg-mahogany-950 border-t border-mahogany-800 text-sepia-500 text-sm text-center py-7 mt-16">
    <p class="font-serif tracking-wide text-sepia-400">&copy; {{ date('Y') }} Biblioteca de Alejandría</p>
    <p class="text-xs mt-1">Universidad UPED &mdash; Programación Aplicada 1</p>
</footer>

@stack('scripts')
</body>
</html>
