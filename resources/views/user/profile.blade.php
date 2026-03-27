@extends('layouts.app')

@section('title', 'Mi Perfil — Biblioteca de Alejandría')

@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lib-animate">

    {{-- Header --}}
    <div class="flex items-center gap-5 mb-8">
        <div class="w-16 h-16 rounded-full bg-mahogany-900 flex items-center justify-center text-gold-400 font-bold text-2xl shrink-0 ring-2 ring-parchment-300/30">
            {{ strtoupper(substr($user->first_name, 0, 1)) }}
        </div>
        <div>
            <h1 class="text-xl font-serif font-semibold text-parchment-100">{{ $user->full_name }}</h1>
            <p class="text-parchment-300 text-sm">{{ $user->email }}</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ tab: 'loans' }">

        {{-- Tab buttons --}}
        <div class="flex border-b border-parchment-300/40 mb-6">
            <button @click="tab = 'loans'"
                    :class="tab === 'loans' ? 'border-gold-400 text-gold-400' : 'border-transparent text-parchment-300 hover:text-parchment-100'"
                    class="mr-8 pb-3 text-sm font-medium border-b-2 transition-colors duration-200">
                Mis Libros Prestados
                <span class="ml-2 bg-gold-500/20 text-gold-400 text-xs px-2 py-0.5 rounded-full">
                    {{ $loans->count() }}
                </span>
            </button>
            <button @click="tab = 'info'"
                    :class="tab === 'info' ? 'border-gold-400 text-gold-400' : 'border-transparent text-parchment-300 hover:text-parchment-100'"
                    class="pb-3 text-sm font-medium border-b-2 transition-colors duration-200">
                Mis Datos
            </button>
        </div>

        {{-- Tab: Loans --}}
        <div x-show="tab === 'loans'">
            @if($loans->isEmpty())
                <div class="text-center py-16 text-sepia-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-sepia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <p class="text-sm">No tienes préstamos registrados.</p>
                </div>
            @else
            <div class="bg-parchment-50 border border-parchment-300 rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-parchment-100 border-b border-parchment-400">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Libro</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Fecha préstamo</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Devolución</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-parchment-300">
                        @foreach($loans as $loan)
                        <tr class="hover:bg-parchment-100 transition-colors duration-150">
                            <td class="px-4 py-3">
                                <p class="font-medium text-mahogany-900">{{ $loan->book->title }}</p>
                                <p class="text-xs text-sepia-400">
                                    {{ $loan->book->authors->pluck('full_name')->join(', ') }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-sepia-600">{{ $loan->loan_date->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-sepia-600">
                                {{ $loan->return_date?->format('d/m/Y') ?? '—' }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusMap = [
                                        'pending'  => ['text' => 'Pendiente', 'class' => 'bg-yellow-100 text-yellow-700'],
                                        'active'   => ['text' => 'Activo',    'class' => 'bg-green-100 text-green-700'],
                                        'returned' => ['text' => 'Devuelto',  'class' => 'bg-parchment-200 text-sepia-600'],
                                        'overdue'  => ['text' => 'Vencido',   'class' => 'bg-red-100 text-red-700'],
                                    ];
                                    $s = $statusMap[$loan->status] ?? $statusMap['active'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $s['class'] }}">
                                    {{ $s['text'] }}
                                </span>
                                @if($loan->status === 'pending' && $loan->qr_token)
                                <div x-data="{ verQr: false, confirmarCancelacion: false }" class="mt-2 space-y-2">

                                    {{-- Formulario oculto de cancelación --}}
                                    <form id="form-cancelar-{{ $loan->id }}" method="POST"
                                          action="{{ route('loans.cancel', $loan) }}">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <div class="flex flex-wrap gap-2">
                                        <button type="button" @click="verQr = !verQr"
                                                class="text-xs font-medium text-gold-700 bg-gold-500/10 hover:bg-gold-500/20 px-2.5 py-1 rounded-lg transition-colors duration-150">
                                            <span x-text="verQr ? 'Ocultar QR' : 'Ver QR'"></span>
                                        </button>
                                        <button type="button" @click="confirmarCancelacion = true"
                                                class="text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition-colors duration-150">
                                            Cancelar reserva
                                        </button>
                                    </div>

                                    {{-- Panel QR colapsable --}}
                                    <div x-show="verQr"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="p-3 bg-white border border-parchment-300 rounded-xl text-center"
                                         style="display:none;">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&margin=6&data={{ urlencode(route('admin.loans.show', $loan)) }}"
                                             alt="QR préstamo #{{ $loan->id }}"
                                             width="140" height="140"
                                             class="mx-auto rounded">
                                        <p class="text-xs text-sepia-400 mt-2">Presenta en la biblioteca</p>
                                    </div>

                                    {{-- Modal de confirmación de cancelación --}}
                                    <div x-show="confirmarCancelacion"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0"
                                         x-transition:enter-end="opacity-100"
                                         x-transition:leave="transition ease-in duration-150"
                                         x-transition:leave-start="opacity-100"
                                         x-transition:leave-end="opacity-0"
                                         class="fixed inset-0 z-50 flex items-center justify-center px-4"
                                         style="display:none;">
                                        <div class="absolute inset-0 bg-mahogany-950/60 backdrop-blur-sm"
                                             @click="confirmarCancelacion = false"></div>
                                        <div x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 scale-100"
                                             x-transition:leave-end="opacity-0 scale-95"
                                             class="relative w-full max-w-md bg-parchment-50 rounded-2xl shadow-xl border border-parchment-300 p-6">
                                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </div>
                                            <h2 class="text-center text-lg font-serif font-semibold text-mahogany-900 mb-2">
                                                ¿Cancelar la reserva?
                                            </h2>
                                            <p class="text-center text-sm text-sepia-600 mb-1">
                                                Estás a punto de cancelar tu reserva de:
                                            </p>
                                            <p class="text-center text-sm font-semibold text-mahogany-900 mb-5 leading-snug">
                                                "{{ $loan->book->title }}"
                                            </p>
                                            <p class="text-center text-xs text-sepia-400 mb-6">
                                                El libro quedará disponible nuevamente para otros usuarios.
                                            </p>
                                            <div class="flex gap-3">
                                                <button type="button" @click="confirmarCancelacion = false"
                                                        class="btn-ghost flex-1 justify-center">
                                                    Volver
                                                </button>
                                                <button type="submit" form="form-cancelar-{{ $loan->id }}"
                                                        class="btn-danger flex-1 justify-center">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Sí, cancelar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
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
            <div class="bg-parchment-50 border border-parchment-300 rounded-2xl p-6">
                <dl class="divide-y divide-parchment-300">
                    @foreach([
                        ['label' => 'Nombre',    'value' => $user->first_name],
                        ['label' => 'Apellido',  'value' => $user->last_name],
                        ['label' => 'Email',     'value' => $user->email],
                        ['label' => 'Rol',       'value' => ucfirst($user->role)],
                        ['label' => 'Miembro desde', 'value' => $user->created_at->format('d/m/Y')],
                    ] as $field)
                    <div class="py-4 grid grid-cols-3">
                        <dt class="text-sm font-medium text-sepia-500">{{ $field['label'] }}</dt>
                        <dd class="text-sm text-mahogany-900 col-span-2">{{ $field['value'] }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>
        </div>

    </div>
</div>

@endsection
