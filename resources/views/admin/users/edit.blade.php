@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-parchment-50 border border-gold-500 rounded-lg text-sm text-sepia-600 flex items-center gap-2">
    <svg class="w-4 h-4 text-gold-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
    </svg>
    {{ session('success') }}
</div>
@endif

<div class="max-w-2xl space-y-5 lib-animate">

    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('admin.users.index') }}" class="text-sepia-400 hover:text-mahogany-900 transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h2 class="text-base font-serif font-semibold text-mahogany-900">Editar: {{ $user->full_name }}</h2>
    </div>

    {{-- Datos generales --}}
    <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-sepia-500 uppercase tracking-wide mb-4">Datos del usuario</h3>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            @include('admin.users._form')
            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-parchment-300">
                <button type="submit"
                        class="px-5 py-2 bg-gold-500 hover:bg-gold-600 text-mahogany-900 font-semibold rounded-lg text-sm
                               transition-all duration-200 hover:shadow-sm active:scale-[0.98]">
                    Guardar cambios
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-sepia-500 hover:text-mahogany-900 transition-colors duration-200">
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="bg-parchment-50 border border-parchment-300 rounded-xl shadow-sm p-6">
        <h3 class="text-sm font-semibold text-sepia-500 uppercase tracking-wide mb-4">Cambiar contraseña</h3>
        <form method="POST" action="{{ route('admin.users.change-password', $user) }}">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-sepia-600 mb-1">
                        Nueva contraseña <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" autocomplete="new-password"
                           class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                                  focus:outline-none focus:ring-2 focus:ring-gold-500
                                  {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
                    @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-sepia-600 mb-1">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-parchment-400 rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                                  focus:outline-none focus:ring-2 focus:ring-gold-500">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit"
                        class="px-5 py-2 bg-mahogany-800 hover:bg-mahogany-900 text-parchment-100 font-semibold rounded-lg text-sm
                               transition-all duration-200 hover:shadow-sm active:scale-[0.98]">
                    Actualizar contraseña
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
