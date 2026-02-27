@extends('layouts.admin')

@section('title', 'Préstamo #' . $loan->id)

@section('content')

@php
$statusLabels = ['active' => 'Activo', 'returned' => 'Devuelto', 'overdue' => 'Vencido'];
$statusColors = [
    'active'   => 'bg-green-100 text-green-800 border-green-200',
    'returned' => 'bg-blue-100 text-blue-800 border-blue-200',
    'overdue'  => 'bg-red-100 text-red-800 border-red-200',
];
@endphp

{{-- Back + actions bar --}}
<div class="flex items-center justify-between mb-5">
    <a href="{{ route('admin.loans.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Préstamos
    </a>

    <div class="flex items-center gap-2">
        @if($loan->status !== 'returned')
        <form method="POST" action="{{ route('admin.loans.return', $loan) }}">
            @csrf @method('PATCH')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg text-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Marcar como devuelto
            </button>
        </form>
        @endif

        <form method="POST" action="{{ route('admin.loans.destroy', $loan) }}"
              onsubmit="return confirm('¿Eliminar este préstamo? Esta acción no se puede deshacer.')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 font-semibold rounded-lg text-sm border border-red-200 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Eliminar préstamo
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Left column: Loan info + User --}}
    <div class="lg:col-span-1 space-y-5">

        {{-- Estado del préstamo --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Préstamo #{{ $loan->id }}</h3>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Estado</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusColors[$loan->status] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                        {{ $statusLabels[$loan->status] ?? $loan->status }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Fecha de préstamo</span>
                    <span class="text-sm font-medium text-gray-800">{{ $loan->loan_date->format('d/m/Y') }}</span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Fecha de devolución</span>
                    @if($loan->return_date)
                        <span class="text-sm font-medium text-gray-800">{{ $loan->return_date->format('d/m/Y') }}</span>
                    @else
                        <span class="text-sm text-gray-400">Pendiente</span>
                    @endif
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-500">Registrado</span>
                    <span class="text-sm text-gray-500">{{ $loan->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        </div>

        {{-- Datos del usuario --}}
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Usuario</h3>

            <div class="flex items-center gap-3 mb-4">
                <div class="w-11 h-11 rounded-full bg-slate-800 flex items-center justify-center text-amber-400 font-bold flex-shrink-0">
                    {{ strtoupper(substr($loan->user->first_name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $loan->user->full_name }}</p>
                    <p class="text-xs text-gray-400">{{ $loan->user->email }}</p>
                </div>
            </div>

            <div class="text-xs text-gray-500">
                ID de usuario: <span class="font-mono text-gray-700">{{ $loan->user->id }}</span>
            </div>
        </div>

    </div>

    {{-- Right column: Book info --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm p-5">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Libro prestado</h3>

            <div class="flex gap-5">
                {{-- Cover --}}
                <div class="flex-shrink-0">
                    @if($loan->book->book_cover)
                        <img src="{{ Storage::url($loan->book->book_cover) }}"
                             alt="{{ $loan->book->title }}"
                             class="w-24 h-36 object-cover rounded-lg shadow">
                    @else
                        <div class="w-24 h-36 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Details --}}
                <div class="flex-1 min-w-0 space-y-3">
                    <div>
                        <p class="text-lg font-bold text-gray-900">{{ $loan->book->title }}</p>
                        @if($loan->book->publisher)
                            <p class="text-sm text-gray-400">{{ $loan->book->publisher }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-0.5">Categoría</p>
                            @if($loan->book->category)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    {{ $loan->book->category->name }}
                                </span>
                            @else
                                <span class="text-gray-400">Sin categoría</span>
                            @endif
                        </div>

                        <div>
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-0.5">ISBN</p>
                            <p class="font-mono text-gray-700 text-xs">{{ $loan->book->isbn ?? '—' }}</p>
                        </div>

                        <div class="col-span-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Autores</p>
                            @if($loan->book->authors->isNotEmpty())
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($loan->book->authors as $author)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                                            {{ $author->full_name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Autor desconocido</span>
                            @endif
                        </div>

                        @if($loan->book->summary)
                        <div class="col-span-2">
                            <p class="text-xs text-gray-400 uppercase tracking-wide font-semibold mb-1">Sinopsis</p>
                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-4">{{ $loan->book->summary }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
