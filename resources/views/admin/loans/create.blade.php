@extends('layouts.admin')

@section('title', 'Nuevo Préstamo')

@section('content')

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-base font-semibold text-gray-700">Nuevo Préstamo</h2>
        <p class="text-sm text-gray-400">Registra un préstamo de libro físico</p>
    </div>
    <a href="{{ route('admin.loans.index') }}"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Volver a Préstamos
    </a>
</div>

<div class="max-w-xl">
    <div class="bg-white rounded-xl shadow-sm p-6">

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
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Usuario <span class="text-red-500">*</span>
                </label>
                <select name="user_id"
                        class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('user_id') border-red-400 bg-red-50 @else border-gray-300 @enderror">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Libro <span class="text-red-500">*</span>
                </label>
                <select name="book_id"
                        class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('book_id') border-red-400 bg-red-50 @else border-gray-300 @enderror">
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
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Fecha de préstamo <span class="text-red-500">*</span>
                </label>
                <input type="date" name="loan_date"
                       value="{{ old('loan_date', now()->toDateString()) }}"
                       class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('loan_date') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                @error('loan_date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm transition"
                        @if($books->isEmpty()) disabled @endif>
                    Registrar préstamo
                </button>
                <a href="{{ route('admin.loans.index') }}"
                   class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 font-medium rounded-lg text-sm transition">
                    Cancelar
                </a>
            </div>
        </form>

    </div>
</div>

@endsection
