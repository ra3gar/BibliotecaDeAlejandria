@extends('layouts.admin')

@section('title', 'Libros')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-base font-semibold text-gray-700">Gestión de Libros</h2>
        <p class="text-sm text-gray-400">{{ $books->total() }} libros en inventario</p>
    </div>
    <a href="{{ route('admin.books.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Libro
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-16">Portada</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Título</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">ISBN</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoría</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Autores</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($books as $book)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    @if($book->book_cover)
                        <img src="{{ Storage::url($book->book_cover) }}" alt="{{ $book->title }}"
                             class="w-10 h-14 object-cover rounded shadow-sm">
                    @else
                        <div class="w-10 h-14 bg-gray-100 rounded flex items-center justify-center">
                            <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                            </svg>
                        </div>
                    @endif
                </td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $book->title }}</p>
                    @if($book->publisher)
                    <p class="text-xs text-gray-400">{{ $book->publisher }}</p>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $book->isbn ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($book->category)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                        {{ $book->category->name }}
                    </span>
                    @else
                    <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    {{ $book->authors->pluck('full_name')->join(', ') ?: '—' }}
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.books.edit', $book) }}"
                           class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.books.destroy', $book) }}"
                              onsubmit="return confirm('¿Eliminar «{{ $book->title }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                    No hay libros registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($books->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $books->links() }}
    </div>
    @endif
</div>

@endsection
