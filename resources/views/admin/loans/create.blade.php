@extends('layouts.admin')

@section('title', 'Nuevo Préstamo')

@section('content')

<div class="flex items-center justify-between mb-5 lib-animate">
    <div>
        <h2 class="text-base font-serif font-semibold text-mahogany-900">Nuevo Préstamo</h2>
        <p class="text-sm text-sepia-400">Registra un préstamo de libro físico</p>
    </div>
    <a href="{{ route('admin.loans.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-sepia-500 hover:text-mahogany-900 transition-colors duration-200">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Préstamos
    </a>
</div>

<div class="max-w-xl lib-animate">
    <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-6">

        @if($errors->any())
        <div class="mb-5 p-4 bg-red-50 border border-red-200 rounded-lg">
            <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.loans.store') }}" class="space-y-5">
            @csrf

            {{-- Usuario --}}
            <div>
                <label class="block text-sm font-medium text-sepia-600 mb-1">
                    Usuario <span class="text-red-500">*</span>
                </label>
                <select name="user_id"
                        class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                               focus:outline-none focus:ring-2 focus:ring-gold-500
                               @error('user_id') border-red-400 bg-red-50 @else border-parchment-400 @enderror">
                    <option value="">— Seleccionar usuario —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->full_name }} — {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('user_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Libro --}}
            <div>
                <label class="block text-sm font-medium text-sepia-600 mb-1">
                    Libro <span class="text-red-500">*</span>
                </label>
                <select name="book_id"
                        class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                               focus:outline-none focus:ring-2 focus:ring-gold-500
                               @error('book_id') border-red-400 bg-red-50 @else border-parchment-400 @enderror">
                    <option value="">— Seleccionar libro disponible —</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} ({{ $book->available_copies }} disponible{{ $book->available_copies !== 1 ? 's' : '' }})
                        </option>
                    @endforeach
                </select>
                @error('book_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                @if($books->isEmpty())
                    <p class="mt-1 text-xs text-red-500">No hay libros con copias disponibles actualmente.</p>
                @endif
            </div>

            {{-- Fecha de préstamo --}}
            <div>
                <label class="block text-sm font-medium text-sepia-600 mb-1">
                    Fecha de préstamo <span class="text-red-500">*</span>
                </label>
                <input type="date" name="loan_date"
                       value="{{ old('loan_date', now()->toDateString()) }}"
                       class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                              focus:outline-none focus:ring-2 focus:ring-gold-500
                              @error('loan_date') border-red-400 bg-red-50 @else border-parchment-400 @enderror">
                @error('loan_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-gold-500 hover:bg-gold-600 text-mahogany-900 font-semibold rounded-lg text-sm
                               transition-all duration-200 hover:shadow-sm active:scale-[0.98]
                               disabled:opacity-50 disabled:cursor-not-allowed"
                        @if($books->isEmpty()) disabled @endif>
                    Registrar préstamo
                </button>
                <a href="{{ route('admin.loans.index') }}"
                   class="px-5 py-2.5 bg-parchment-200 hover:bg-parchment-300 text-sepia-600 font-medium rounded-lg text-sm transition-colors duration-150">
                    Cancelar
                </a>
            </div>
        </form>

    </div>
</div>

@endsection
