@extends('layouts.app')

@section('title', 'Catálogo — Biblioteca de Alejandría')

@section('content')

{{-- Hero / Carousel --}}
<section class="py-12" x-data="carousel({{ $latestBooks->count() }})"
         style="background-color: #110C09; background-image: linear-gradient(160deg, #110C09 60%, #1C1410 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-7">
            <div>
                <p class="text-gold-500 text-xs font-semibold uppercase tracking-widest mb-2">Últimas incorporaciones</p>
                <h1 class="text-parchment-100 text-2xl font-serif font-semibold">Novedades del catálogo</h1>
            </div>
            <div class="hidden md:block w-16 h-px mb-2" style="background: linear-gradient(to right, rgba(201,151,74,0.6), transparent);"></div>
        </div>

        @if($latestBooks->isEmpty())
            <p class="text-sepia-400">Aún no hay libros registrados.</p>
        @else
        <div class="relative overflow-hidden">
            <div class="flex transition-transform duration-500 ease-in-out"
                 :style="'transform: translateX(-' + (current * (100 / visibleCount)) + '%)'">

                @foreach($latestBooks as $book)
                <div class="shrink-0 px-3" :style="'width: calc(100% / ' + visibleCount + ')'">
                    <a href="{{ route('books.show', $book) }}"
                       class="block group bg-mahogany-800 rounded-xl overflow-hidden border border-mahogany-700
                              hover:border-gold-500 hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
                        @if($book->book_cover)
                            <img src="{{ Storage::url($book->book_cover) }}"
                                 alt="{{ $book->title }}"
                                 class="w-full h-56 object-contain group-hover:scale-105 transition-transform duration-400">
                        @else
                            <div class="w-full h-56 bg-mahogany-700 flex items-center justify-center">
                                <svg class="w-14 h-14 text-sepia-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <p class="text-parchment-100 font-semibold text-sm truncate group-hover:text-gold-400 transition-colors duration-200">
                                {{ $book->title }}
                            </p>
                            <p class="text-sepia-400 text-xs mt-1 truncate">
                                {{ $book->authors->pluck('full_name')->join(', ') ?: 'Autor desconocido' }}
                            </p>
                            <div class="mt-2">
                                @if($book->isAvailable())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-500/20 text-green-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                        Disponible
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-500/20 text-red-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                        Agotado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach

            </div>

            {{-- Controls --}}
            @if($latestBooks->count() > 1)
            <button @click="prev()"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 w-10 h-10 bg-parchment-50 rounded-full
                           shadow-lg flex items-center justify-center text-mahogany-800 hover:bg-gold-500 hover:text-mahogany-900
                           transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="next()"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 w-10 h-10 bg-parchment-50 rounded-full
                           shadow-lg flex items-center justify-center text-mahogany-800 hover:bg-gold-500 hover:text-mahogany-900
                           transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @endif
        </div>
        @endif
    </div>
</section>

{{-- Categories --}}
<section id="categorias" class="py-14 bg-parchment-50/80 backdrop-blur-sm">
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

{{-- Authors --}}
<section id="autores" class="py-14 bg-parchment-100/80 backdrop-blur-sm">
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

@push('scripts')
<script>
function carousel(total) {
    return {
        current: 0,
        visibleCount: 1,
        total: total,
        get max() { return Math.max(0, this.total - this.visibleCount); },
        init() {
            this.updateVisible();
            window.addEventListener('resize', () => this.updateVisible());
        },
        updateVisible() {
            const w = window.innerWidth;
            if (w >= 1024) this.visibleCount = 4;
            else if (w >= 768) this.visibleCount = 3;
            else if (w >= 640) this.visibleCount = 2;
            else this.visibleCount = 1;
            if (this.current > this.max) this.current = this.max;
        },
        next() { if (this.current < this.max) this.current++; },
        prev() { if (this.current > 0) this.current--; },
    }
}
</script>
@endpush
