<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin — @yield('title', 'Biblioteca de Alejandría')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('page-styles')
    <style>
        main .bg-parchment-50 {
            background-color: rgba(254, 253, 249, 0.90) !important;
            backdrop-filter: blur(6px);
        }
        main .border-parchment-300 {
            border-color: rgba(228, 217, 200, 0.5) !important;
        }
    </style>
</head>
<body class="font-sans antialiased"
      style="background-image: url('{{ asset('images/Fondo2.jpg') }}'); background-size: cover; background-attachment: fixed; background-position: center;">

<div class="flex h-screen overflow-hidden" x-data="{ open: false }">

    {{-- Overlay (mobile) --}}
    <div x-show="open" @click="open = false"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-20 bg-mahogany-950/70 lg:hidden"></div>

    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-30 w-64 flex-shrink-0 flex flex-col
                  transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
           :class="open ? 'translate-x-0' : '-translate-x-full'"
           style="background-color: #110C09;">

        {{-- Logo + Brand --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b" style="border-color: rgba(61,43,31,0.6);">
            <img src="{{ asset('images/logo.png') }}" alt="Logo"
                 class="w-9 h-9 object-contain shrink-0 drop-shadow"
                 style="filter: drop-shadow(0 1px 3px rgba(0,0,0,0.5));">
            <div class="min-w-0">
                <p class="font-serif font-semibold text-parchment-100 text-sm leading-tight">Biblioteca de Alejandría</p>
                <p class="text-xs mt-0.5" style="color: rgba(90,72,56,0.8);">Panel de administración</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group
                      {{ request()->routeIs('admin.dashboard') ? 'bg-gold-500/15 text-gold-400 border border-gold-500/20' : 'text-parchment-400 hover:bg-mahogany-800/60 hover:text-parchment-100 border border-transparent' }}">
                <svg class="w-4 h-4 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-gold-400' : 'text-sepia-500 group-hover:text-parchment-300' }} transition-colors duration-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Sección: Mantenimientos --}}
            <div class="pt-4 pb-1.5 px-3">
                <p class="text-xs font-semibold uppercase tracking-widest" style="color: rgba(90,72,56,0.6);">
                    Mantenimientos
                </p>
            </div>

            @foreach([
                ['route' => 'admin.users.index',      'label' => 'Usuarios',    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.books.index',      'label' => 'Libros',      'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                ['route' => 'admin.categories.index', 'label' => 'Categorías',  'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['route' => 'admin.authors.index',    'label' => 'Autores',     'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                ['route' => 'admin.loans.index',      'label' => 'Préstamos',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
            ] as $item)
            @php $isActive = request()->routeIs($item['route'].'*'); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200 group border
                      {{ $isActive ? 'bg-gold-500/15 text-gold-400 border-gold-500/20' : 'text-parchment-400 hover:bg-mahogany-800/60 hover:text-parchment-100 border-transparent' }}">
                <svg class="w-4 h-4 shrink-0 {{ $isActive ? 'text-gold-400' : 'text-sepia-500 group-hover:text-parchment-300' }} transition-colors duration-200"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        {{-- Pie del sidebar: Usuario + Logout --}}
        <div class="px-3 py-4 border-t" style="border-color: rgba(61,43,31,0.6);">
            <div class="flex items-center gap-3 px-2 mb-3">
                <div class="w-8 h-8 rounded-full bg-gold-500 flex items-center justify-center text-mahogany-900 font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->first_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-medium text-parchment-100 truncate">{{ auth()->user()->full_name }}</p>
                    <p class="text-xs" style="color: rgba(90,72,56,0.8);">Administrador</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-sm text-sepia-400 hover:bg-mahogany-800/60 hover:text-parchment-200 transition-all duration-200">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="h-14 flex items-center px-4 lg:px-6 gap-3 backdrop-blur-sm border-b"
                style="background-color: rgba(254,253,249,0.92); border-color: rgba(228,217,200,0.5);">
            <button @click="open = !open"
                    class="lg:hidden p-2 rounded-xl text-sepia-500 hover:bg-parchment-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-base font-serif font-semibold text-mahogany-900">@yield('title', 'Dashboard')</h1>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">

            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="mb-5 flex items-center gap-3 rounded-xl bg-parchment-50 border border-gold-500/40 px-4 py-3 shadow-sm">
                <div class="w-7 h-7 rounded-full bg-gold-500/15 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-gold-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <p class="text-sm text-sepia-700 font-medium">{{ session('success') }}</p>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
