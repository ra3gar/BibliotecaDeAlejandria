@extends('layouts.admin')

@section('title', 'Nueva Categoría')

@section('content')
<div class="max-w-xl lib-animate">

    {{-- Breadcrumb header --}}
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('admin.categories.index') }}"
           class="p-2 rounded-xl text-sepia-400 hover:text-mahogany-900 hover:bg-parchment-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <p class="text-xs text-sepia-400 uppercase tracking-wider">Categorías</p>
            <h2 class="text-lg font-serif font-semibold text-mahogany-900 leading-tight">Nueva Categoría</h2>
        </div>
    </div>

    <div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm p-7">
        <form method="POST" action="{{ route('admin.categories.store') }}">
            @csrf
            @include('admin.categories._form')
            <div class="flex items-center gap-3 mt-7 pt-6 border-t border-parchment-300">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar categoría
                </button>
                <a href="{{ route('admin.categories.index') }}" class="btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection
