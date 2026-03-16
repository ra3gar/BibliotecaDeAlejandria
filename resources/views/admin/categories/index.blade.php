@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')

<div class="flex items-center justify-between mb-5 lib-animate">
    <div>
        <h2 class="text-base font-serif font-semibold text-mahogany-900">Gestión de Categorías</h2>
        <p class="text-sm text-sepia-400">{{ $categories->total() }} categorías</p>
    </div>
    <a href="{{ route('admin.categories.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gold-500 hover:bg-gold-600 text-mahogany-900 font-semibold rounded-lg text-sm
              transition-all duration-200 hover:shadow-md active:scale-[0.98]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nueva Categoría
    </a>
</div>

<div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm overflow-hidden lib-animate">
    <table class="w-full text-sm">
        <thead class="bg-parchment-100 border-b border-parchment-400">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Nombre</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Descripción</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Libros</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-parchment-300">
            @forelse($categories as $category)
            <tr class="hover:bg-parchment-100 transition-colors duration-150">
                <td class="px-4 py-3 font-medium text-mahogany-900">{{ $category->name }}</td>
                <td class="px-4 py-3 text-sepia-500 max-w-sm truncate">{{ $category->description ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-midnight-100 text-midnight-800">
                        {{ $category->books_count }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="px-3 py-1 text-xs font-medium text-midnight-800 bg-midnight-100 hover:bg-midnight-100/70 rounded-lg transition-colors duration-150">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.categories.destroy', $category) }}"
                              onsubmit="return confirm('¿Eliminar la categoría «{{ $category->name }}»?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-colors duration-150">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-4 py-10 text-center text-sepia-400 text-sm">
                    No hay categorías registradas.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($categories->hasPages())
    <div class="px-4 py-3 border-t border-parchment-300">
        {{ $categories->links() }}
    </div>
    @endif
</div>

@endsection
