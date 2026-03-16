@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-6 lib-animate">

    {{-- Fila 1: Tarjetas de estadísticas --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 lib-stagger">
        <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-5 flex items-center gap-4
                    hover:-translate-y-0.5 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-serif font-semibold text-mahogany-900">{{ $totalBooks }}</p>
                <p class="text-sm text-sepia-500">Total Libros</p>
                @if($booksOutOfStock > 0)
                <p class="text-xs text-red-500 mt-0.5">{{ $booksOutOfStock }} agotado{{ $booksOutOfStock !== 1 ? 's' : '' }}</p>
                @endif
            </div>
        </div>

        <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-5 flex items-center gap-4
                    hover:-translate-y-0.5 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-gold-500 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-mahogany-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-serif font-semibold text-mahogany-900">{{ $totalLoans }}</p>
                <p class="text-sm text-sepia-500">Préstamos Totales</p>
            </div>
        </div>

        <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-5 flex items-center gap-4
                    hover:-translate-y-0.5 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-serif font-semibold text-mahogany-900">{{ $activeLoans }}</p>
                <p class="text-sm text-sepia-500">Préstamos Activos</p>
                @if($overdueLoans > 0)
                <p class="text-xs text-red-500 mt-0.5">{{ $overdueLoans }} vencido{{ $overdueLoans !== 1 ? 's' : '' }}</p>
                @endif
            </div>
        </div>

        <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-5 flex items-center gap-4
                    hover:-translate-y-0.5 hover:shadow-md transition-all duration-300">
            <div class="w-12 h-12 bg-midnight-800 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-parchment-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-serif font-semibold text-mahogany-900">{{ $totalUsers }}</p>
                <p class="text-sm text-sepia-500">Usuarios Registrados</p>
                @if($inactiveUsers > 0)
                <p class="text-xs text-orange-500 mt-0.5">{{ $inactiveUsers }} inactivo{{ $inactiveUsers !== 1 ? 's' : '' }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Fila 2: Accesos rápidos + Préstamos recientes --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Accesos rápidos --}}
        <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-6">
            <h2 class="text-base font-serif font-semibold text-mahogany-900 mb-4">Accesos rápidos</h2>
            <div class="grid grid-cols-2 gap-3 lib-stagger">
                @foreach([
                    ['route' => 'admin.books.create',     'label' => 'Nuevo Libro',     'icon' => 'M12 4v16m8-8H4'],
                    ['route' => 'admin.loans.create',     'label' => 'Nuevo Préstamo',  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['route' => 'admin.users.index',      'label' => 'Ver Usuarios',    'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'admin.categories.index', 'label' => 'Categorías',      'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
                ] as $link)
                <a href="{{ route($link['route']) }}"
                   class="flex flex-col items-center gap-2 p-4 rounded-xl border border-parchment-300
                          hover:border-gold-500 hover:bg-gold-500/5 transition-all duration-200 text-center group">
                    <svg class="w-7 h-7 text-sepia-400 group-hover:text-gold-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $link['icon'] }}"/>
                    </svg>
                    <span class="text-sm text-sepia-600 group-hover:text-mahogany-900 font-medium transition-colors duration-200">{{ $link['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Préstamos recientes --}}
        <div class="lg:col-span-2 bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-serif font-semibold text-mahogany-900">Préstamos recientes</h2>
                <a href="{{ route('admin.loans.index') }}" class="text-xs text-gold-600 hover:text-gold-700 font-medium transition-colors duration-200">Ver todos</a>
            </div>
            @if($recentLoans->isEmpty())
                <p class="text-sm text-sepia-400 py-4 text-center">No hay préstamos registrados.</p>
            @else
            <div class="divide-y divide-parchment-300">
                @foreach($recentLoans as $loan)
                <div class="py-3 flex items-center justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-mahogany-900 truncate">{{ $loan->book->title }}</p>
                        <p class="text-xs text-sepia-400">{{ $loan->user->full_name }} — {{ $loan->loan_date->format('d/m/Y') }}</p>
                    </div>
                    @php
                        $colors = ['active' => 'bg-green-100 text-green-800', 'returned' => 'bg-blue-100 text-blue-800', 'overdue' => 'bg-red-100 text-red-800'];
                        $labels = ['active' => 'Activo', 'returned' => 'Devuelto', 'overdue' => 'Vencido'];
                    @endphp
                    <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $colors[$loan->status] ?? 'bg-parchment-200 text-sepia-600' }}">
                        {{ $labels[$loan->status] ?? $loan->status }}
                    </span>
                </div>
                @endforeach
            </div>
            @endif
        </div>

    </div>

    {{-- Fila 3: Auditoría --}}
    <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-base font-serif font-semibold text-mahogany-900">Auditoría reciente</h2>
        </div>
        @if($recentAuditLogs->isEmpty())
            <p class="text-sm text-sepia-400 text-center py-4">Sin actividad registrada todavía.</p>
        @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide border-b border-parchment-300">
                        <th class="pb-2 pr-4">Acción</th>
                        <th class="pb-2 pr-4">Descripción</th>
                        <th class="pb-2 pr-4">Usuario</th>
                        <th class="pb-2">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-parchment-200">
                    @foreach($recentAuditLogs as $log)
                    @php
                        $actionColor = ['created' => 'bg-green-100 text-green-700', 'updated' => 'bg-blue-100 text-blue-700', 'deleted' => 'bg-red-100 text-red-700', 'login_failed' => 'bg-orange-100 text-orange-700'];
                        $actionLabel = ['created' => 'Creado', 'updated' => 'Editado', 'deleted' => 'Eliminado', 'login_failed' => 'Login Fallido'];
                    @endphp
                    <tr>
                        <td class="py-2 pr-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $actionColor[$log->action] ?? 'bg-parchment-200 text-sepia-600' }}">
                                {{ $actionLabel[$log->action] ?? $log->action }}
                            </span>
                        </td>
                        <td class="py-2 pr-4 text-sepia-600">{{ $log->description }}</td>
                        <td class="py-2 pr-4 text-sepia-500">{{ $log->user?->full_name ?? 'Sistema' }}</td>
                        <td class="py-2 text-sepia-400 text-xs whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

@endsection
