@extends('layouts.admin')

@section('title', 'Nueva Categoría')

@section('content')
<div class="max-w-xl lib-animate">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('admin.categories.index') }}" class="text-sepia-400 hover:text-mahogany-900 transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-base font-serif font-semibold text-mahogany-900">Nueva Categoría</h2>
    </div>

    <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            @include('admin.categories._form')
            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-parchment-300">
                <button type="submit"
                        class="px-5 py-2 bg-gold-500 hover:bg-gold-600 text-mahogany-900 font-semibold rounded-lg text-sm
                               transition-all duration-200 hover:shadow-sm active:scale-[0.98]">
                    Guardar categoría
                </button>
                <a href="{{ route('admin.categories.index') }}" class="text-sm text-sepia-500 hover:text-mahogany-900 transition-colors duration-200">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
