@extends('layouts.admin')

@section('title', 'Autores')

@section('content')

<div class="flex items-center justify-between mb-5 lib-animate">
    <div>
        <h2 class="text-base font-serif font-semibold text-mahogany-900">Gestión de Autores</h2>
        <p class="text-sm text-sepia-400">{{ $authors->total() }} autores</p>
    </div>
    <a href="{{ route('admin.authors.create') }}"
       class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Autor
    </a>
</div>

<div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm overflow-hidden lib-animate">
    <table class="w-full text-sm">
        <thead class="bg-parchment-100 border-b border-parchment-400">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Autor</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Biografía</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Libros</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-parchment-300">
            @forelse($authors as $author)
            <tr class="hover:bg-parchment-100 transition-colors duration-150">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-mahogany-900 flex items-center justify-center text-gold-400 font-bold text-xs shrink-0">
                            {{ strtoupper(substr($author->first_name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-mahogany-900">{{ $author->full_name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-sepia-500 max-w-sm">
                    <p class="truncate">{{ $author->bio ?? '—' }}</p>
                </td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                        {{ $author->books_count }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.authors.edit', $author) }}"
                           class="px-3 py-1 text-xs font-medium text-midnight-800 bg-midnight-100 hover:bg-midnight-100/70 rounded-xl transition-colors duration-150">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.authors.destroy', $author) }}"
                              onsubmit="return confirm('¿Eliminar al autor {{ $author->full_name }}?')">
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
                <td colspan="4" class="px-4 py-10 text-center text-sepia-400 text-sm">
                    No hay autores registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($authors->hasPages())
    <div class="px-4 py-3 border-t border-parchment-300">
        {{ $authors->links() }}
    </div>
    @endif
</div>

@endsection
