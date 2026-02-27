@extends('layouts.app')

@section('title', $pageTitle . ' — Biblioteca de Alejandría')

@section('content')

{{-- Header de resultados --}}
<section class="bg-slate-900 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-amber-400 text-xs font-semibold uppercase tracking-widest mb-1">Resultados filtrados</p>
            <h1 class="text-white text-2xl font-bold">{{ $pageTitle }}</h1>
            <p class="text-slate-400 text-sm mt-1">{{ $books->count() }} {{ $books->count() === 1 ? 'libro encontrado' : 'libros encontrados' }}</p>
        </div>
        <a href="{{ route('catalogo') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-400 text-slate-900 font-semibold text-sm rounded-lg transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
            Ver todo el catálogo
        </a>
    </div>
</section>

{{-- Grid de libros filtrados --}}
<section class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        @if($books->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-gray-500 text-lg font-medium">No se encontraron libros en esta sección.</p>
                <p class="text-gray-400 text-sm mt-1">Intenta explorar otra categoría o autor.</p>
                <a href="{{ route('catalogo') }}"
                   class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-slate-900 font-semibold text-sm rounded-lg transition">
                    Ver todo el catálogo
                </a>
            </div>
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach($books as $book)
                <a href="{{ route('books.show', $book) }}"
                   class="block group bg-white border border-gray-200 rounded-xl overflow-hidden hover:border-amber-400 hover:shadow-md transition">
                    @if($book->book_cover)
                        <img src="{{ Storage::url($book->book_cover) }}"
                             alt="{{ $book->title }}"
                             class="w-full h-56 object-cover group-hover:scale-105 transition duration-300">
                    @else
                        <div class="w-full h-56 bg-slate-100 flex items-center justify-center">
                            <svg class="w-14 h-14 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-3">
                        <p class="text-gray-900 font-semibold text-sm truncate group-hover:text-amber-600 transition">
                            {{ $book->title }}
                        </p>
                        <p class="text-gray-400 text-xs mt-0.5 truncate">
                            {{ $book->authors->pluck('full_name')->join(', ') ?: 'Autor desconocido' }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>
        @endif

    </div>
</section>

{{-- Explorar por Categoría --}}
<section class="py-12 bg-white border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Explorar por Categoría</h2>

        @if($categories->isEmpty())
            <p class="text-gray-400 text-sm">No hay categorías registradas.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('catalogo.categoria', $category) }}"
               class="block bg-stone-100 hover:bg-amber-50 border border-stone-200 hover:border-amber-300
                      rounded-xl p-4 text-center transition group">
                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-700 group-hover:text-amber-700 truncate">
                    {{ $category->name }}
                </p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $category->books_count }} libros</p>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Explorar por Autor --}}
<section class="py-12 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Explorar por Autor</h2>

        @if($authors->isEmpty())
            <p class="text-gray-400 text-sm">No hay autores registrados.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($authors as $author)
            <a href="{{ route('catalogo.autor', $author) }}"
               class="flex bg-white rounded-xl border border-gray-200 p-4 items-center gap-3 hover:border-amber-300 hover:shadow-sm transition group">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-amber-400 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($author->first_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-amber-700 truncate">
                        {{ $author->full_name }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $author->books_count }} {{ Str::plural('libro', $author->books_count) }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</section>

@endsection
