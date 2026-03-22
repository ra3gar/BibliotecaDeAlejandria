@extends('layouts.admin')

@section('title', 'Libros')

@section('content')

<div class="flex items-center justify-between mb-5 lib-animate">
    <div>
        <h2 class="text-base font-serif font-semibold text-mahogany-900">Gestión de Libros</h2>
        <p class="text-sm text-sepia-400">{{ $books->total() }} libros en inventario</p>
    </div>
    <a href="{{ route('admin.books.create') }}"
       class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Libro
    </a>
</div>

<div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm overflow-hidden lib-animate">
    <table class="w-full text-sm">
        <thead class="bg-parchment-100 border-b border-parchment-400">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide w-16">Portada</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Título</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">ISBN</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Categoría</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Autores</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Stock</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-parchment-300">
            @forelse($books as $book)
            <tr class="hover:bg-parchment-100 transition-colors duration-150">
                <td class="px-4 py-3">
                    @if($book->book_cover)
                        <img src="{{ Storage::url($book->book_cover) }}" alt="{{ $book->title }}"
                             class="w-10 h-14 object-contain rounded shadow-sm">
                    @else
                        <div class="w-10 h-14 bg-parchment-200 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-sepia-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                            </svg>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <p class="font-medium text-mahogany-900">{{ $book->title }}</p>
                    @if($book->publisher)
                    <p class="text-xs text-sepia-400">{{ $book->publisher }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-sepia-500 font-mono text-xs">{{ $book->isbn ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($book->category)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gold-500/15 text-gold-700">
                        {{ $book->category->name }}
                    </span>
                    @else
                    <span class="text-sepia-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sepia-500 text-xs">
                    {{ $book->authors->pluck('full_name')->join(', ') ?: '—' }}
                </td>
                <td class="px-4 py-3">
                    @if($book->available_copies > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            {{ $book->available_copies }}/{{ $book->stock_total }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                            Agotado
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.books.edit', $book) }}"
                           class="px-3 py-1 text-xs font-medium text-midnight-800 bg-midnight-100 hover:bg-midnight-100/70 rounded-xl transition-colors duration-150">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.books.destroy', $book) }}"
                              onsubmit="return confirm('¿Eliminar «{{ $book->title }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-xl transition-colors duration-150">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-sepia-400 text-sm">
                    No hay libros registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($books->hasPages())
    <div class="px-4 py-3 border-t border-parchment-300">
        {{ $books->links() }}
    </div>
    @endif
</div>

@endsection
