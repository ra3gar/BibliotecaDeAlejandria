@extends('layouts.app')

@section('title', $book->title . ' — Biblioteca de Alejandría')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lib-animate">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-parchment-300 mb-6">
        <a href="{{ route('catalogo') }}" class="hover:text-gold-400 transition-colors duration-200">Catálogo</a>
        <svg class="w-4 h-4 text-parchment-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-parchment-100 font-medium truncate max-w-xs">{{ $book->title }}</span>
    </nav>

    <div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm overflow-hidden">
        <div class="flex flex-col md:flex-row">

            {{-- Cover --}}
            <div class="shrink-0 bg-parchment-100 flex items-center justify-center p-6 md:min-h-96 md:w-72 border-b md:border-b-0 md:border-r border-parchment-300">
                @if($book->book_cover)
                    <img src="{{ Storage::url($book->book_cover) }}"
                         alt="{{ $book->title }}"
                         class="max-w-full max-h-96 w-auto h-auto object-contain rounded shadow-md">
                @else
                    <div class="flex items-center justify-center w-44 h-64 bg-parchment-200 rounded">
                        <svg class="w-16 h-16 text-sepia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="flex-1 p-8">

                {{-- Category badge + availability --}}
                <div class="flex items-center gap-2 mb-3 flex-wrap">
                    @if($book->category)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gold-500/15 text-gold-700">
                        {{ $book->category->name }}
                    </span>
                    @endif
                    @if($book->isAvailable())
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Disponible ({{ $book->available_copies }} {{ $book->available_copies === 1 ? 'copia' : 'copias' }})
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Agotado
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl font-serif font-semibold text-mahogany-900 mb-2">{{ $book->title }}</h1>

                {{-- Authors --}}
                @if($book->authors->isNotEmpty())
                <p class="text-sepia-500 text-sm mb-6">
                    por <span class="font-medium text-mahogany-900">{{ $book->authors->pluck('full_name')->join(', ') }}</span>
                </p>
                @endif

                {{-- Summary --}}
                @if($book->summary)
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-sepia-500 uppercase tracking-wide mb-2">Resumen</h2>
                    <p class="text-sepia-600 text-sm leading-relaxed">{{ $book->summary }}</p>
                </div>
                @endif

                {{-- Mensajes de sesión --}}
                @if(session('success'))
                    <div class="mb-4 px-4 py-2 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 px-4 py-2 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Bloque de acción principal --}}
                @if($userLoan)

                    {{-- El usuario ya tiene una reserva de este libro --}}
                    @if($userLoan->status === 'pending')
                    <div class="mb-6" x-data="{ verQr: false, confirmarCancelacion: false }">
                        {{-- Estado de la reserva --}}
                        <div class="flex items-center gap-2 mb-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500"></span>
                                Reserva pendiente de retiro
                            </span>
                        </div>

                        {{-- Formulario de cancelación (oculto, se envía desde el modal) --}}
                        <form id="form-cancelar" method="POST" action="{{ route('loans.cancel', $userLoan) }}">
                            @csrf
                            @method('DELETE')
                        </form>

                        {{-- Botones: Ver QR y Cancelar --}}
                        <div class="flex flex-wrap gap-3 mb-4">
                            <button type="button" @click="verQr = !verQr" class="btn-primary">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                                <span x-text="verQr ? 'Ocultar QR' : 'Ver mi QR'"></span>
                            </button>

                            <button type="button" @click="confirmarCancelacion = true" class="btn-danger">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelar reserva
                            </button>
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
                                    "{{ $book->title }}"
                                </p>
                                <p class="text-center text-xs text-sepia-400 mb-6">
                                    El libro quedará disponible nuevamente para otros usuarios.
                                </p>
                                <div class="flex gap-3">
                                    <button type="button" @click="confirmarCancelacion = false"
                                            class="btn-ghost flex-1 justify-center">
                                        Volver
                                    </button>
                                    <button type="submit" form="form-cancelar"
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

                        {{-- Panel QR colapsable --}}
                        <div x-show="verQr"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="bg-parchment-100 border border-parchment-300 rounded-2xl p-5 text-center"
                             style="display:none;">
                            <p class="text-xs font-semibold text-sepia-500 uppercase tracking-wide mb-3">
                                Código QR — Préstamo #{{ $userLoan->id }}
                            </p>
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&margin=8&data={{ urlencode(route('admin.loans.show', $userLoan)) }}"
                                 alt="QR préstamo #{{ $userLoan->id }}"
                                 width="180" height="180"
                                 class="mx-auto rounded-xl shadow-sm">
                            <p class="text-xs text-sepia-400 mt-3">
                                Preséntalo en la biblioteca para retirar el libro
                            </p>
                        </div>
                    </div>

                    @elseif($userLoan->status === 'active')
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Libro en tu poder — devuélvelo en la biblioteca cuando termines
                        </span>
                    </div>
                    @endif

                @elseif($book->isAvailable())

                    {{-- Sin reserva previa: mostrar botón de reserva con modal --}}
                    <div class="mb-6" x-data="{ abierto: false }">
                        <form id="form-reserva" method="POST" action="{{ route('books.reserve', $book) }}">
                            @csrf
                        </form>

                        <button type="button" @click="abierto = true" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            Reservar este libro
                        </button>

                        {{-- Modal de confirmación --}}
                        <div x-show="abierto"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="fixed inset-0 z-50 flex items-center justify-center px-4"
                             style="display:none;">
                            <div class="absolute inset-0 bg-mahogany-950/60 backdrop-blur-sm"
                                 @click="abierto = false"></div>
                            <div x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="relative w-full max-w-md bg-parchment-50 rounded-2xl shadow-xl border border-parchment-300 p-6">
                                <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gold-500/15 mx-auto mb-4">
                                    <svg class="w-6 h-6 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                </div>
                                <h2 class="text-center text-lg font-serif font-semibold text-mahogany-900 mb-2">
                                    ¿Confirmar reserva?
                                </h2>
                                <p class="text-center text-sm text-sepia-600 mb-1">Estás a punto de reservar:</p>
                                <p class="text-center text-sm font-semibold text-mahogany-900 mb-5 leading-snug">
                                    "{{ $book->title }}"
                                </p>
                                <p class="text-center text-xs text-sepia-400 mb-6">
                                    Recibirás un QR por correo electrónico para retirar el libro en la biblioteca.
                                </p>
                                <div class="flex gap-3">
                                    <button type="button" @click="abierto = false" class="btn-ghost flex-1 justify-center">
                                        Cancelar
                                    </button>
                                    <button type="submit" form="form-reserva" class="btn-primary flex-1 justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Sí, confirmar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    {{-- Sin stock --}}
                    <div class="mb-6">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            Sin copias disponibles en este momento
                        </span>
                    </div>
                @endif

                {{-- Technical details --}}
                <div class="border-t border-parchment-300 pt-5">
                    <h2 class="text-sm font-semibold text-sepia-500 uppercase tracking-wide mb-3">Detalles técnicos</h2>
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        @if($book->isbn)
                        <div>
                            <dt class="text-sepia-400 font-medium">ISBN</dt>
                            <dd class="text-mahogany-900 font-mono">{{ $book->isbn }}</dd>
                        </div>
                        @endif
                        @if($book->publisher)
                        <div>
                            <dt class="text-sepia-400 font-medium">Editorial</dt>
                            <dd class="text-mahogany-900">{{ $book->publisher }}</dd>
                        </div>
                        @endif
                        @if($book->published_at)
                        <div>
                            <dt class="text-sepia-400 font-medium">Publicación</dt>
                            <dd class="text-mahogany-900">{{ $book->published_at->format('Y') }}</dd>
                        </div>
                        @endif
                        @if($book->category)
                        <div>
                            <dt class="text-sepia-400 font-medium">Categoría</dt>
                            <dd class="text-mahogany-900">{{ $book->category->name }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

            </div>
        </div>
    </div>

    {{-- Authors section --}}
    @if($book->authors->isNotEmpty())
    <div class="mt-6">
        <div class="inline-flex items-center gap-3 mb-3 bg-mahogany-950/70 backdrop-blur-sm px-4 py-2 rounded-xl">
            <div class="w-1 h-5 rounded-full bg-gold-500 shrink-0"></div>
            <h2 class="text-sm font-semibold text-parchment-100 uppercase tracking-wide">
                {{ $book->authors->count() === 1 ? 'Acerca del autor' : 'Acerca de los autores' }}
            </h2>
        </div>
        <div class="space-y-4 lib-stagger">
            @foreach($book->authors as $author)
            <div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm p-5 flex gap-5">
                {{-- Photo --}}
                <div class="shrink-0">
                    @if($author->photo_url)
                        <img src="{{ $author->photo_url }}"
                             alt="{{ $author->full_name }}"
                             class="w-16 h-16 rounded-full object-cover border-2 border-parchment-300">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gold-500/15 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                {{-- Info --}}
                <div class="min-w-0">
                    <p class="font-serif font-semibold text-mahogany-900">{{ $author->full_name }}</p>
                    @if($author->bio)
                        <p class="mt-1 text-sm text-sepia-600 leading-relaxed">{{ $author->bio }}</p>
                    @else
                        <p class="mt-1 text-xs text-sepia-400 italic">Sin biografía disponible.</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Back button --}}
    <div class="mt-6">
        <a href="{{ route('catalogo') }}"
           class="inline-flex items-center gap-2 text-sm text-gold-400 hover:text-gold-300 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al catálogo
        </a>
    </div>

</div>

@endsection
