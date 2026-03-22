@extends('layouts.app')

@section('title', $pageTitle . ' — Biblioteca de Alejandría')

@push('page-styles')
<style>
    body { background-image: url('{{ asset('images/Fondo2.jpg') }}'); background-size: cover; background-attachment: fixed; background-position: center; }
</style>
@endpush

@section('content')

{{-- Header de resultados --}}
<section class="bg-mahogany-950 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-gold-500 text-xs font-semibold uppercase tracking-widest mb-1">Resultados filtrados</p>
            <h1 class="text-parchment-100 text-2xl font-serif font-semibold">{{ $pageTitle }}</h1>
            <p class="text-sepia-400 text-sm mt-1">{{ $books->count() }} {{ $books->count() === 1 ? 'libro encontrado' : 'libros encontrados' }}</p>
        </div>
        <a href="{{ route('catalogo') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gold-500 hover:bg-gold-400 text-mahogany-900 font-semibold text-sm rounded-xl
                  transition-all duration-200 hover:shadow-md active:scale-[0.98] self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            Ver todo el catálogo
        </a>
    </div>
</section>

{{-- Grid de libros filtrados --}}
<section class="py-12 bg-parchment-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($books->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <svg class="w-16 h-16 text-sepia-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-mahogany-900 text-lg font-serif font-medium">No se encontraron libros en esta sección.</p>
                <p class="text-sepia-400 text-sm mt-1">Intenta explorar otra categoría o autor.</p>
                <a href="{{ route('catalogo') }}"
                   class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-mahogany-900 font-semibold text-sm rounded-xl
                          transition-all duration-200 hover:shadow-md active:scale-[0.98]">
                    Ver todo el catálogo
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6 lib-stagger">
                @foreach($books as $book)
                <a href="{{ route('books.show', $book) }}"
                   class="block group bg-parchment-50 border border-parchment-300 rounded-xl overflow-hidden
                          hover:border-gold-500 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300">
                    @if($book->book_cover)
                        <img src="{{ Storage::url($book->book_cover) }}"
                             alt="{{ $book->title }}"
                             class="w-full h-56 object-contain group-hover:scale-105 transition-transform duration-400">
                    @else
                        <div class="w-full h-56 bg-parchment-200 flex items-center justify-center">
                            <svg class="w-14 h-14 text-sepia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-3">
                        <p class="text-mahogany-900 font-semibold text-sm truncate group-hover:text-gold-600 transition-colors duration-200">
                            {{ $book->title }}
                        </p>
                        <p class="text-sepia-400 text-xs mt-0.5 truncate">
                            {{ $book->authors->pluck('full_name')->join(', ') ?: 'Autor desconocido' }}
                        </p>
                        <div class="mt-2">
                            @if($book->isAvailable())
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                    Disponible
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    Agotado
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif

    </div>
</section>

{{-- Explorar por Categoría --}}
<section class="py-14 bg-parchment-100 border-t border-parchment-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-7">
            <div class="w-1 h-7 rounded-full bg-gold-500"></div>
            <h2 class="text-xl font-serif font-semibold text-mahogany-900">Explorar por Categoría</h2>
        </div>

        @if($categories->isEmpty())
            <p class="text-sepia-400 text-sm">No hay categorías registradas.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 lib-stagger">
            @foreach($categories as $category)
            <a href="{{ route('catalogo.categoria', $category) }}"
               class="block bg-parchment-50 border border-parchment-300 hover:border-gold-500 hover:bg-gold-500/5
                      rounded-xl p-4 text-center transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm group">
                <div class="w-10 h-10 bg-gold-500/15 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-gold-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-mahogany-900 group-hover:text-gold-700 truncate transition-colors duration-200">
                    {{ $category->name }}
                </p>
                <p class="text-xs text-sepia-400 mt-0.5">{{ $category->books_count }} libros</p>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Explorar por Autor --}}
<section class="py-14 bg-parchment-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-7">
            <div class="w-1 h-7 rounded-full bg-gold-500"></div>
            <h2 class="text-xl font-serif font-semibold text-mahogany-900">Explorar por Autor</h2>
        </div>

        @if($authors->isEmpty())
            <p class="text-sepia-400 text-sm">No hay autores registrados.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 lib-stagger">
            @foreach($authors as $author)
            <a href="{{ route('catalogo.autor', $author) }}"
               class="flex bg-parchment-50 rounded-xl border border-parchment-300 p-4 items-center gap-3
                      hover:border-gold-500 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group">
                <div class="w-10 h-10 rounded-full bg-mahogany-900 flex items-center justify-center text-gold-400 font-bold text-sm shrink-0">
                    {{ strtoupper(substr($author->first_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-mahogany-900 group-hover:text-gold-700 truncate transition-colors duration-200">
                        {{ $author->full_name }}
                    </p>
                    <p class="text-xs text-sepia-400">{{ $author->books_count }} {{ Str::plural('libro', $author->books_count) }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

@endsection
