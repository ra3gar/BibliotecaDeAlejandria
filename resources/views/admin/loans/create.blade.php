@extends('layouts.admin')

@section('title', 'Nuevo Préstamo')

@section('content')

<div class="flex items-center justify-between mb-6 lib-animate">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.loans.index') }}"
           class="p-2 rounded-xl text-sepia-400 hover:text-mahogany-900 hover:bg-parchment-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <p class="text-xs text-sepia-400 uppercase tracking-wider">Préstamos</p>
            <h2 class="text-lg font-serif font-semibold text-mahogany-900 leading-tight">Nuevo Préstamo</h2>
        </div>
    </div>
</div>

<div class="max-w-xl lib-animate">
    <div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm p-7">

        @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
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
                <label class="form-label">Usuario <span class="text-red-500 normal-case">*</span></label>
                <select name="user_id"
                        class="form-input {{ $errors->has('user_id') ? 'border-red-300 bg-red-50' : '' }}">
                    <option value="">— Seleccionar usuario —</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->full_name }} — {{ $user->email }}
                        </option>
                    @endforeach
                </select>
                @error('user_id') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Libro --}}
            <div>
                <label class="form-label">Libro <span class="text-red-500 normal-case">*</span></label>
                <select name="book_id"
                        class="form-input {{ $errors->has('book_id') ? 'border-red-300 bg-red-50' : '' }}">
                    <option value="">— Seleccionar libro disponible —</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                            {{ $book->title }} ({{ $book->available_copies }} disponible{{ $book->available_copies !== 1 ? 's' : '' }})
                        </option>
                    @endforeach
                </select>
                @error('book_id') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                @if($books->isEmpty())
                    <p class="mt-1.5 text-xs text-red-500">No hay libros con copias disponibles actualmente.</p>
                @endif
            </div>

            {{-- Fecha de préstamo --}}
            <div>
                <label class="form-label">Fecha de préstamo <span class="text-red-500 normal-case">*</span></label>
                <input type="date" name="loan_date"
                       value="{{ old('loan_date', now()->toDateString()) }}"
                       class="form-input {{ $errors->has('loan_date') ? 'border-red-300 bg-red-50' : '' }}">
                @error('loan_date') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2 border-t border-parchment-300 mt-2">
                <button type="submit" class="btn-primary {{ $books->isEmpty() ? 'opacity-50 pointer-events-none' : '' }}"
                        @if($books->isEmpty()) disabled @endif>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Registrar préstamo
                </button>
                <a href="{{ route('admin.loans.index') }}" class="btn-ghost">Cancelar</a>
            </div>
        </form>

    </div>
</div>

@endsection
