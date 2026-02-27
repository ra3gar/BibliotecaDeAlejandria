@extends('layouts.app')

@section('title', 'Catálogo — Biblioteca de Alejandría')

@section('content')

{{-- Hero / Carousel --}}
<section class="bg-slate-900 py-12" x-data="carousel({{ $latestBooks->count() }})">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-amber-400 text-xs font-semibold uppercase tracking-widest mb-2">Últimas incorporaciones</h2>
        <h1 class="text-white text-2xl font-bold mb-8">Novedades del catálogo</h1>

        @if($latestBooks->isEmpty())
            <p class="text-slate-400">Aún no hay libros registrados.</p>
        @else
        <div class="relative overflow-hidden">
            <div class="flex transition-transform duration-500 ease-in-out"
                 :style="'transform: translateX(-' + (current * (100 / visibleCount)) + '%)'">

                @foreach($latestBooks as $book)
                <div class="flex-shrink-0 px-3" style="width: calc(100% / 4)">
                    <a href="{{ route('books.show', $book) }}"
                       class="block group bg-slate-800 rounded-xl overflow-hidden hover:ring-2 hover:ring-amber-500 transition">
                        @if($book->book_cover)
                            <img src="{{ Storage::url($book->book_cover) }}"
                                 alt="{{ $book->title }}"
                                 class="w-full h-56 object-cover group-hover:scale-105 transition duration-300">
                        @else
                            <div class="w-full h-56 bg-slate-700 flex items-center justify-center">
                                <svg class="w-14 h-14 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                        @endif
                        <div class="p-4">
                            <p class="text-white font-semibold text-sm truncate group-hover:text-amber-400 transition">
                                {{ $book->title }}
                            </p>
                            <p class="text-slate-400 text-xs mt-1 truncate">
                                {{ $book->authors->pluck('full_name')->join(', ') ?: 'Autor desconocido' }}
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach

            </div>

            {{-- Controls --}}
            @if($latestBooks->count() > 4)
            <button @click="prev()"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-slate-700 hover:bg-amber-500 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button @click="next()"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-2 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center text-slate-700 hover:bg-amber-500 hover:text-white transition">
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
<section id="categorias" class="py-12 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Explorar por Categoría</h2>

        @if($categories->isEmpty())
            <p class="text-gray-400 text-sm">No hay categorías registradas.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
            <div class="bg-stone-100 hover:bg-amber-50 border border-stone-200 hover:border-amber-300
                        rounded-xl p-4 text-center cursor-pointer transition group">
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
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

{{-- Authors --}}
<section id="autores" class="py-12 bg-stone-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Explorar por Autor</h2>

        @if($authors->isEmpty())
            <p class="text-gray-400 text-sm">No hay autores registrados.</p>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
            @foreach($authors as $author)
            <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-3 hover:border-amber-300 hover:shadow-sm transition cursor-pointer group">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-amber-400 font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr($author->first_name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-800 group-hover:text-amber-700 truncate">
                        {{ $author->full_name }}
                    </p>
                    <p class="text-xs text-gray-400">{{ $author->books_count }} {{ Str::plural('libro', $author->books_count) }}</p>
                </div>
            </div>
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
        visibleCount: 4,
        max: Math.max(0, total - 4),
        next() { if (this.current < this.max) this.current++; },
        prev() { if (this.current > 0) this.current--; },
    }
}
</script>
@endpush
