@extends('layouts.app')

@section('title', $book->title . ' — Biblioteca de Alejandría')

@section('content')

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
        <a href="{{ route('catalogo') }}" class="hover:text-amber-500 transition">Catálogo</a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
        <span class="text-gray-600 truncate max-w-xs">{{ $book->title }}</span>
    </nav>

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="flex flex-col md:flex-row">

            {{-- Cover --}}
            <div class="md:w-72 flex-shrink-0 bg-slate-800">
                @if($book->book_cover)
                    <img src="{{ Storage::url($book->book_cover) }}"
                         alt="{{ $book->title }}"
                         class="w-full h-full object-cover md:min-h-96">
                @else
                    <div class="w-full h-64 md:h-full flex items-center justify-center">
                        <svg class="w-20 h-20 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                @endif
            </div>

            {{-- Details --}}
            <div class="flex-1 p-8">

                {{-- Category badge --}}
                @if($book->category)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 mb-3">
                    {{ $book->category->name }}
                </span>
                @endif

                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>

                {{-- Authors --}}
                @if($book->authors->isNotEmpty())
                <p class="text-gray-500 text-sm mb-6">
                    por <span class="font-medium text-gray-700">{{ $book->authors->pluck('full_name')->join(', ') }}</span>
                </p>
                @endif

                {{-- Summary --}}
                @if($book->summary)
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-2">Resumen</h2>
                    <p class="text-gray-700 text-sm leading-relaxed">{{ $book->summary }}</p>
                </div>
                @endif

                {{-- Technical details --}}
                <div class="border-t border-gray-100 pt-5">
                    <h2 class="text-sm font-semibold text-gray-600 uppercase tracking-wide mb-3">Detalles técnicos</h2>
                    <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                        @if($book->isbn)
                        <div>
                            <dt class="text-gray-400 font-medium">ISBN</dt>
                            <dd class="text-gray-700 font-mono">{{ $book->isbn }}</dd>
                        </div>
                        @endif
                        @if($book->publisher)
                        <div>
                            <dt class="text-gray-400 font-medium">Editorial</dt>
                            <dd class="text-gray-700">{{ $book->publisher }}</dd>
                        </div>
                        @endif
                        @if($book->published_at)
                        <div>
                            <dt class="text-gray-400 font-medium">Publicación</dt>
                            <dd class="text-gray-700">{{ $book->published_at->format('Y') }}</dd>
                        </div>
                        @endif
                        @if($book->category)
                        <div>
                            <dt class="text-gray-400 font-medium">Categoría</dt>
                            <dd class="text-gray-700">{{ $book->category->name }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

            </div>
        </div>
    </div>

    {{-- Back button --}}
    <div class="mt-6">
        <a href="{{ route('catalogo') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-amber-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver al catálogo
        </a>
    </div>

</div>

@endsection
