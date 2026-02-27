@extends('layouts.admin')

@section('title', 'Autores')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-base font-semibold text-gray-700">Gestión de Autores</h2>
        <p class="text-sm text-gray-400">{{ $authors->total() }} autores</p>
    </div>
    <a href="{{ route('admin.authors.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Autor
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Autor</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Biografía</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Libros</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($authors as $author)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-amber-400 font-bold text-xs flex-shrink-0">
                            {{ strtoupper(substr($author->first_name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $author->full_name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-500 max-w-sm">
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
                           class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                            Editar
                        </a>
                        <form method="POST" action="{{ route('admin.authors.destroy', $author) }}"
                              onsubmit="return confirm('¿Eliminar al autor {{ $author->full_name }}?')">
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
                <td colspan="4" class="px-4 py-10 text-center text-gray-400 text-sm">
                    No hay autores registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($authors->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $authors->links() }}
    </div>
    @endif
</div>

@endsection
