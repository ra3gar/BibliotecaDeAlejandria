@extends('layouts.app')

@section('title', $author->full_name . ' — Biblioteca de Alejandría')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lib-animate">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-parchment-300 mb-6">
        <a href="{{ route('catalogo') }}" class="hover:text-gold-400 transition-colors duration-200">Catálogo</a>
        <svg class="w-4 h-4 text-parchment-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <a href="{{ route('catalogo') }}#autores" class="hover:text-gold-400 transition-colors duration-200">Autores</a>
        <svg class="w-4 h-4 text-parchment-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-parchment-100 font-medium truncate max-w-xs">{{ $author->full_name }}</span>
    </nav>

    {{-- Perfil del autor --}}
    <div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm overflow-hidden mb-10">
        <div class="flex flex-col sm:flex-row">

            {{-- Foto del autor --}}
            <div class="shrink-0 bg-parchment-100 border-b sm:border-b-0 sm:border-r border-parchment-300
                        flex items-center justify-center p-8 sm:w-56">
                @if($author->photo_url)
                    <div class="relative">
                        <div class="absolute -inset-1.5 rounded-full"
                             style="background: linear-gradient(135deg, #C9974A 0%, #8B5E1A 50%, #C9974A 100%); padding: 3px;">
                        </div>
                        <img src="{{ $author->photo_url }}"
                             alt="{{ $author->full_name }}"
                             class="relative w-36 h-36 rounded-full object-cover border-4 border-parchment-100 shadow-md">
                    </div>
                @else
                    <div class="w-36 h-36 rounded-full bg-mahogany-900 border-4 border-gold-500/40 shadow-md
                                flex items-center justify-center">
                        <span class="font-serif font-bold text-5xl text-gold-400">
                            {{ strtoupper(substr($author->first_name, 0, 1)) }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Info del autor --}}
            <div class="flex-1 p-7">
                <p class="text-gold-600 text-xs font-semibold uppercase tracking-widest mb-1">Autor</p>
                <h1 class="font-serif font-bold text-3xl text-mahogany-900 leading-tight mb-3">
                    {{ $author->full_name }}
                </h1>

                <div class="flex items-center gap-2 mb-5">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                                 bg-gold-500/15 text-gold-700 border border-gold-500/30">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        {{ $author->books->count() }} {{ $author->books->count() === 1 ? 'libro' : 'libros' }} en catálogo
                    </span>
                </div>

                @if($author->bio)
                    <div class="w-12 h-px bg-gold-500/40 mb-4"></div>
                    <p class="text-sepia-700 leading-relaxed text-[0.95rem]">{{ $author->bio }}</p>
                @else
                    <p class="text-sepia-400 text-sm italic">Sin biografía disponible.</p>
                @endif
            </div>

        </div>
    </div>

    {{-- Galería de libros --}}
    <div class="mb-4 flex items-center gap-4">
        <div class="w-1 h-7 rounded-full bg-gold-500"></div>
        <h2 class="text-xl font-serif font-semibold text-parchment-100">
            Libros de {{ $author->first_name }}
        </h2>
    </div>

    @if($author->books->isEmpty())
        <div class="bg-parchment-50/80 backdrop-blur-sm border border-parchment-300 rounded-2xl py-16 flex flex-col items-center text-center">
            <svg class="w-14 h-14 text-sepia-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <p class="font-serif text-mahogany-900 font-medium">Sin libros registrados</p>
            <p class="text-sepia-400 text-sm mt-1">Este autor aún no tiene títulos en el catálogo.</p>
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-5 lib-stagger">
            @foreach($author->books as $book)
            <a href="{{ route('books.show', $book) }}"
               class="block group bg-parchment-50 border border-parchment-300 rounded-xl overflow-hidden
                      hover:border-gold-500 hover:shadow-lg hover:-translate-y-1.5 transition-all duration-300">

                {{-- Portada --}}
                @if($book->book_cover)
                    <img src="{{ Storage::url($book->book_cover) }}"
                         alt="{{ $book->title }}"
                         class="w-full h-52 object-contain group-hover:scale-105 transition-transform duration-400">
                @else
                    <div class="w-full h-52 bg-parchment-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-sepia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                @endif

                {{-- Info --}}
                <div class="p-3">
                    <p class="text-mahogany-900 font-semibold text-sm truncate group-hover:text-gold-600 transition-colors duration-200">
                        {{ $book->title }}
                    </p>
                    @if($book->category)
                        <p class="text-sepia-400 text-xs mt-0.5 truncate">{{ $book->category->name }}</p>
                    @endif
                    <div class="mt-2 flex items-center justify-between">
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
                        <span class="text-xs text-sepia-400">{{ $book->available_copies }}/{{ $book->stock_total }}</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif

</div>

@endsection
