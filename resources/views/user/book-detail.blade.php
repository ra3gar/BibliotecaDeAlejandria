@extends('layouts.app')

@section('title', $book->title . ' — Biblioteca de Alejandría')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lib-animate">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-sepia-400 mb-6">
        <a href="{{ route('catalogo') }}" class="hover:text-gold-600 transition-colors duration-200">Catálogo</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-sepia-600 truncate max-w-xs">{{ $book->title }}</span>
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

                {{-- Reserve action --}}
                @if($book->isAvailable())
                <div class="mb-6">
                    @if(session('success'))
                        <div class="mb-3 px-4 py-2 bg-green-50 border border-green-200 rounded-xl text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="mb-3 px-4 py-2 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('books.reserve', $book) }}">
                        @csrf
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            Reservar este libro
                        </button>
                    </form>
                </div>
                @else
                <div class="mb-6">
                    @if(session('error'))
                        <div class="px-4 py-2 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif
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
        <h2 class="text-sm font-semibold text-sepia-500 uppercase tracking-wide mb-3">
            {{ $book->authors->count() === 1 ? 'Acerca del autor' : 'Acerca de los autores' }}
        </h2>
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
           class="inline-flex items-center gap-2 text-sm text-sepia-500 hover:text-gold-600 transition-colors duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al catálogo
        </a>
    </div>

</div>

@endsection
