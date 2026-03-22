@extends('layouts.admin')

@section('title', 'Préstamos')

@section('content')

@php
$statusLabels = ['pending' => 'Pendiente', 'active' => 'Activo', 'returned' => 'Devuelto', 'overdue' => 'Vencido'];
$statusColors = [
    'pending'  => 'bg-yellow-100 text-yellow-800',
    'active'   => 'bg-green-100 text-green-800',
    'returned' => 'bg-blue-100 text-blue-800',
    'overdue'  => 'bg-red-100 text-red-800',
];
@endphp

{{-- Header --}}
<div class="flex items-center justify-between mb-5 lib-animate">
    <div>
        <h2 class="text-base font-serif font-semibold text-mahogany-900">Gestión de Préstamos</h2>
        <p class="text-sm text-sepia-400">{{ $loans->total() }} préstamos registrados</p>
    </div>
    <a href="{{ route('admin.loans.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-gold-500 hover:bg-gold-600 text-mahogany-900 font-semibold rounded-lg text-sm
              transition-all duration-200 hover:shadow-md active:scale-[0.98]">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Préstamo
    </a>
</div>

{{-- Filtros --}}
<form method="GET" action="{{ route('admin.loans.index') }}"
      class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-4 mb-5
             grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 lib-animate">

    {{-- Usuario --}}
    <div>
        <label class="block text-xs font-semibold text-sepia-500 uppercase tracking-wide mb-1">Usuario</label>
        <select name="user_id"
                class="w-full rounded-lg border border-parchment-400 bg-parchment-50 text-sm text-mahogany-900 px-3 py-2
                       focus:outline-none focus:ring-2 focus:ring-gold-500">
            <option value="">Todos</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->full_name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Libro --}}
    <div>
        <label class="block text-xs font-semibold text-sepia-500 uppercase tracking-wide mb-1">Título del libro</label>
        <input type="text" name="book" value="{{ request('book') }}"
               placeholder="Buscar por título…"
               class="w-full rounded-lg border border-parchment-400 bg-parchment-50 text-sm text-mahogany-900 px-3 py-2
                      focus:outline-none focus:ring-2 focus:ring-gold-500">
    </div>

    {{-- Categoría --}}
    <div>
        <label class="block text-xs font-semibold text-sepia-500 uppercase tracking-wide mb-1">Categoría</label>
        <select name="category_id"
                class="w-full rounded-lg border border-parchment-400 bg-parchment-50 text-sm text-mahogany-900 px-3 py-2
                       focus:outline-none focus:ring-2 focus:ring-gold-500">
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
        <label class="block text-xs font-semibold text-sepia-500 uppercase tracking-wide mb-1">Autor</label>
        <select name="author_id"
                class="w-full rounded-lg border border-parchment-400 bg-parchment-50 text-sm text-mahogany-900 px-3 py-2
                       focus:outline-none focus:ring-2 focus:ring-gold-500">
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
        <label class="block text-xs font-semibold text-sepia-500 uppercase tracking-wide mb-1">Estado</label>
        <select name="status"
                class="w-full rounded-lg border border-parchment-400 bg-parchment-50 text-sm text-mahogany-900 px-3 py-2
                       focus:outline-none focus:ring-2 focus:ring-gold-500">
            <option value="">Todos</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pendiente</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Activo</option>
            <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Devuelto</option>
            <option value="overdue"  {{ request('status') === 'overdue'  ? 'selected' : '' }}>Vencido</option>
        </select>
    </div>

    {{-- Botones --}}
    <div class="lg:col-span-5 flex items-center gap-2 pt-1">
        <button type="submit"
                class="px-4 py-2 bg-gold-500 hover:bg-gold-600 text-mahogany-900 font-semibold rounded-lg text-sm
                       transition-all duration-200 hover:shadow-sm active:scale-[0.98]">
            Filtrar
        </button>
        <a href="{{ route('admin.loans.index') }}"
           class="px-4 py-2 bg-parchment-200 hover:bg-parchment-300 text-sepia-600 font-medium rounded-lg text-sm transition-colors duration-150">
            Limpiar filtros
        </a>
    </div>
</form>

{{-- Tabla --}}
<div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm overflow-hidden lib-animate">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-parchment-100 border-b border-parchment-400">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide w-12">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Usuario</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Libro</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Categoría</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Autores</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">F. Préstamo</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Estado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-parchment-300">
            @forelse($loans as $loan)
            <tr class="hover:bg-parchment-100 transition-colors duration-150">
                <td class="px-4 py-3 text-sepia-400 font-mono text-xs">{{ $loan->id }}</td>

                <td class="px-4 py-3">
                    <p class="font-medium text-mahogany-900">{{ $loan->user->full_name }}</p>
                    <p class="text-xs text-sepia-400">{{ $loan->user->email }}</p>
                </td>

                <td class="px-4 py-3">
                    <p class="font-medium text-mahogany-900 max-w-xs truncate">{{ $loan->book->title }}</p>
                </td>

                <td class="px-4 py-3">
                    @if($loan->book->category)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gold-500/15 text-gold-700">
                            {{ $loan->book->category->name }}
                        </span>
                    @else
                        <span class="text-sepia-400">—</span>
                    @endif
                </td>

                <td class="px-4 py-3 text-sepia-500 text-xs max-w-35 truncate">
                    {{ $loan->book->authors->pluck('full_name')->join(', ') ?: '—' }}
                </td>

                <td class="px-4 py-3 text-sepia-500 text-xs whitespace-nowrap">
                    {{ $loan->loan_date->format('d/m/Y') }}
                </td>

                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $statusColors[$loan->status] ?? 'bg-parchment-200 text-sepia-600' }}">
                        {{ $statusLabels[$loan->status] ?? $loan->status }}
                    </span>
                </td>

                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2 flex-nowrap">
                        <a href="{{ route('admin.loans.show', $loan) }}"
                           class="px-3 py-1 text-xs font-medium text-sepia-700 bg-parchment-200 hover:bg-parchment-300 rounded-lg transition-colors duration-150">
                            Ver
                        </a>

                        @if($loan->status === 'pending')
                        <form method="POST" action="{{ route('admin.loans.confirm-pickup', $loan) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium text-yellow-700 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors duration-150">
                                Confirmar entrega
                            </button>
                        </form>
                        @endif

                        @if($loan->status !== 'returned')
                        <form method="POST" action="{{ route('admin.loans.return', $loan) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium text-green-700 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-150">
                                Devuelto
                            </button>
                        </form>
                        @endif

                        <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}"
                              onsubmit="return confirm('¿Eliminar préstamo #{{ $loan->id }}? Esta acción no se puede deshacer.')">
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
                <td colspan="8" class="px-4 py-12 text-center text-sepia-400 text-sm">
                    No se encontraron préstamos con los filtros aplicados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($loans->hasPages())
    <div class="px-4 py-3 border-t border-parchment-300">
        {{ $loans->links() }}
    </div>
    @endif
</div>

@endsection
