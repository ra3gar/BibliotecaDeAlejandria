@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6">

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        @foreach([
            ['label' => 'Total Libros',        'value' => $totalBooks,  'color' => 'bg-blue-500',   'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['label' => 'Préstamos Totales',   'value' => $totalLoans,  'color' => 'bg-amber-500',  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
            ['label' => 'Préstamos Activos',   'value' => $activeLoans, 'color' => 'bg-green-500',  'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0'],
            ['label' => 'Usuarios Registrados','value' => $totalUsers,  'color' => 'bg-purple-500', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
        ] as $kpi)
        <div class="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 {{ $kpi['color'] }} rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpi['icon'] }}"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-800">{{ $kpi['value'] }}</p>
                <p class="text-sm text-gray-500">{{ $kpi['label'] }}</p>
            </div>
        </div>
        @endforeach

    </div>

    {{-- Quick access --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h2 class="text-base font-semibold text-gray-700 mb-4">Accesos rápidos</h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @foreach([
                ['route' => 'admin.books.create',      'label' => 'Nuevo Libro',     'icon' => 'M12 4v16m8-8H4'],
                ['route' => 'admin.users.index',       'label' => 'Ver Usuarios',    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['route' => 'admin.categories.index',  'label' => 'Categorías',      'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ['route' => 'admin.authors.index',     'label' => 'Autores',         'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ] as $link)
            <a href="{{ route($link['route']) }}"
               class="flex flex-col items-center gap-2 p-4 rounded-xl border border-gray-200 hover:border-amber-400 hover:bg-amber-50 transition text-center group">
                <svg class="w-7 h-7 text-gray-400 group-hover:text-amber-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                </svg>
                <span class="text-sm text-gray-600 group-hover:text-amber-700 font-medium">{{ $link['label'] }}</span>
            </a>
            @endforeach
        </div>
    </div>

</div>

@endsection
