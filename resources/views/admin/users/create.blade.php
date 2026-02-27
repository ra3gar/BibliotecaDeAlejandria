@extends('layouts.admin')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="max-w-2xl">
    <div class="flex items-center gap-3 mb-5">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-base font-semibold text-gray-700">Nuevo Usuario</h2>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @include('admin.users._form')
            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit"
                        class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm transition">
                    Guardar usuario
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
