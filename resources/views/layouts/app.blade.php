<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca de Alejandría')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-stone-50 font-sans antialiased">

{{-- Navbar --}}
<nav class="bg-slate-900 text-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Left: Logo + Links --}}
            <div class="flex items-center gap-8">
                <a href="{{ route('catalogo') }}" class="flex items-center gap-2 text-amber-400 font-bold text-lg">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
                    </svg>
                    Biblioteca
                </a>
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('catalogo') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium transition
                              {{ request()->routeIs('catalogo') ? 'text-amber-400' : 'text-slate-300 hover:text-white' }}">
                        Inicio
                    </a>
                    <a href="{{ route('catalogo') }}#categorias"
                       class="px-3 py-2 rounded-md text-sm font-medium text-slate-300 hover:text-white transition">
                        Categorías
                    </a>
                    <a href="{{ route('catalogo') }}#autores"
                       class="px-3 py-2 rounded-md text-sm font-medium text-slate-300 hover:text-white transition">
                        Autores
                    </a>
                </div>
            </div>

            {{-- Right: User profile --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-slate-800 transition">
                    <div class="w-8 h-8 rounded-full bg-amber-500 flex items-center justify-center text-slate-900 font-bold text-sm">
                        {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                    </div>
                    <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->full_name }}</span>
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" @click.outside="open = false" x-cloak
                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                    <a href="{{ route('profile') }}"
                       class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Mi perfil
                    </a>
                    <hr class="my-1 border-gray-100">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
<footer class="bg-slate-900 text-slate-400 text-sm text-center py-6 mt-16">
    <p>&copy; {{ date('Y') }} Biblioteca de Alejandría. Todos los derechos reservados.</p>
</footer>

@stack('scripts')
</body>
</html>
