@extends('layouts.app')

@section('title', 'Mi Perfil — Biblioteca de Alejandría')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Header --}}
    <div class="flex items-center gap-5 mb-8">
        <div class="w-16 h-16 rounded-full bg-slate-800 flex items-center justify-center text-amber-400 font-bold text-2xl">
            {{ strtoupper(substr($user->first_name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-900">{{ $user->full_name }}</h1>
            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'loans' }">

        {{-- Tab buttons --}}
        <div class="flex border-b border-gray-200 mb-6">
            <button @click="tab = 'loans'"
                    :class="tab === 'loans' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="mr-8 pb-3 text-sm font-medium border-b-2 transition">
                Mis Libros Prestados
                <span class="ml-2 bg-amber-100 text-amber-700 text-xs px-2 py-0.5 rounded-full">
                    {{ $loans->count() }}
                </span>
            </button>
            <button @click="tab = 'info'"
                    :class="tab === 'info' ? 'border-amber-500 text-amber-600' : 'border-transparent text-gray-500 hover:text-gray-700'"
                    class="pb-3 text-sm font-medium border-b-2 transition">
                Mis Datos
            </button>
        </div>

        {{-- Tab: Loans --}}
        <div x-show="tab === 'loans'">
            @if($loans->isEmpty())
                <div class="text-center py-16 text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm">No tienes préstamos registrados.</p>
                </div>
            @else
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Libro</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Fecha préstamo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Devolución</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($loans as $loan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <p class="font-medium text-gray-800">{{ $loan->book->title }}</p>
                                <p class="text-xs text-gray-400">
                                    {{ $loan->book->authors->pluck('full_name')->join(', ') }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">
                                {{ $loan->return_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusMap = [
                                        'active'   => ['text' => 'Activo',    'class' => 'bg-green-100 text-green-700'],
                                        'returned' => ['text' => 'Devuelto',  'class' => 'bg-gray-100 text-gray-600'],
                                        'overdue'  => ['text' => 'Vencido',   'class' => 'bg-red-100 text-red-700'],
                                    ];
                                    $s = $statusMap[$loan->status] ?? $statusMap['active'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                                    {{ $s['text'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Tab: Personal info --}}
        <div x-show="tab === 'info'" x-cloak>
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <dl class="divide-y divide-gray-100">
                    @foreach([
                        ['label' => 'Nombre',    'value' => $user->first_name],
                        ['label' => 'Apellido',  'value' => $user->last_name],
                        ['label' => 'Email',     'value' => $user->email],
                        ['label' => 'Rol',       'value' => ucfirst($user->role)],
                        ['label' => 'Miembro desde', 'value' => $user->created_at->format('d/m/Y')],
                    ] as $field)
                    <div class="py-4 grid grid-cols-3">
                        <dt class="text-sm font-medium text-gray-500">{{ $field['label'] }}</dt>
                        <dd class="text-sm text-gray-800 col-span-2">{{ $field['value'] }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>
        </div>

    </div>
</div>

@endsection
