@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
    {{ session('success') }}
</div>
@endif

<div class="max-w-2xl space-y-5">

    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-gray-600 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-base font-semibold text-gray-700">Editar: {{ $user->full_name }}</h2>
    </div>

    {{-- Datos generales --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Datos del usuario</h3>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            @include('admin.users._form')
            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-gray-100">
                <button type="submit"
                        class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm transition">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-500 hover:text-gray-700">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="bg-white rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Cambiar contraseña</h3>
        <form method="POST" action="{{ route('admin.users.change-password', $user) }}">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nueva contraseña <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" autocomplete="new-password"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('password') border-red-400 bg-red-50 @else border-gray-300 @enderror">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit"
                        class="px-5 py-2 bg-slate-700 hover:bg-slate-800 text-white font-semibold rounded-lg text-sm transition">
                    Actualizar contraseña
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
