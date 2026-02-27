@extends('layouts.admin')

@section('title', 'Préstamos')

@section('content')

@php
$statusLabels = ['active' => 'Activo', 'returned' => 'Devuelto', 'overdue' => 'Vencido'];
$statusColors = [
    'active'   => 'bg-green-100 text-green-800',
    'returned' => 'bg-blue-100 text-blue-800',
    'overdue'  => 'bg-red-100 text-red-800',
];
@endphp

{{-- Header --}}
<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-base font-semibold text-gray-700">Gestión de Préstamos</h2>
        <p class="text-sm text-gray-400">{{ $loans->total() }} préstamos registrados</p>
    </div>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.loans.index') }}"
      class="bg-white rounded-xl shadow-sm p-4 mb-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

    {{-- Usuario --}}
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Usuario</label>
        <select name="user_id"
                class="w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">Todos</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->full_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Libro (búsqueda por título) --}}
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Título del libro</label>
        <input type="text" name="book" value="{{ request('book') }}"
               placeholder="Buscar por título…"
               class="w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
    </div>

    {{-- Categoría --}}
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Categoría</label>
        <select name="category_id"
                class="w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">Todas</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Autor --}}
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Autor</label>
        <select name="author_id"
                class="w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">Todos</option>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" {{ request('author_id') == $author->id ? 'selected' : '' }}>
                    {{ $author->full_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Estado --}}
    <div>
        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Estado</label>
        <select name="status"
                class="w-full rounded-lg border border-gray-300 text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-400">
            <option value="">Todos</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Activo</option>
            <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Devuelto</option>
            <option value="overdue"  {{ request('status') === 'overdue'  ? 'selected' : '' }}>Vencido</option>
        </select>
    </div>

    {{-- Botones --}}
    <div class="lg:col-span-5 flex items-center gap-2 pt-1">
        <button type="submit"
                class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm transition">
            Filtrar
        </button>
        <a href="{{ route('admin.loans.index') }}"
           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium rounded-lg text-sm transition">
            Limpiar filtros
        </a>
    </div>
</form>

{{-- Tabla --}}
<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide w-12">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Usuario</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Libro</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Categoría</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Autores</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">F. Préstamo</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($loans as $loan)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-4 py-3 text-gray-400 font-mono text-xs">{{ $loan->id }}</td>

                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800">{{ $loan->user->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $loan->user->email }}</p>
                </td>

                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800 max-w-xs truncate">{{ $loan->book->title }}</p>
                </td>

                <td class="px-4 py-3">
                    @if($loan->book->category)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            {{ $loan->book->category->name }}
                        </span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>

                <td class="px-4 py-3 text-gray-500 text-xs max-w-[140px] truncate">
                    {{ $loan->book->authors->pluck('full_name')->join(', ') ?: '—' }}
                </td>

                <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">
                    {{ $loan->loan_date->format('d/m/Y') }}
                </td>

                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$loan->status] ?? 'bg-gray-100 text-gray-600' }}">
                        {{ $statusLabels[$loan->status] ?? $loan->status }}
                    </span>
                </td>

                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2 flex-nowrap">
                        <a href="{{ route('admin.loans.show', $loan) }}"
                           class="px-3 py-1 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                            Ver
                        </a>

                        @if($loan->status !== 'returned')
                        <form method="POST" action="{{ route('admin.loans.return', $loan) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition">
                                Devuelto
                            </button>
                        </form>
                        @endif

                        <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}"
                              onsubmit="return confirm('¿Eliminar préstamo #{{ $loan->id }}? Esta acción no se puede deshacer.')">
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
                <td colspan="8" class="px-4 py-12 text-center text-gray-400 text-sm">
                    No se encontraron préstamos con los filtros aplicados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($loans->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $loans->links() }}
    </div>
    @endif
</div>

@endsection
