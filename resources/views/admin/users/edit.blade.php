@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')

<div class="max-w-2xl space-y-5 lib-animate">

    {{-- Breadcrumb header --}}
    <div class="flex items-center gap-3 mb-1">
        <a href="{{ route('admin.users.index') }}"
           class="p-2 rounded-xl text-sepia-400 hover:text-mahogany-900 hover:bg-parchment-200 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <div>
            <p class="text-xs text-sepia-400 uppercase tracking-wider">Usuarios</p>
            <h2 class="text-lg font-serif font-semibold text-mahogany-900 leading-tight">{{ $user->full_name }}</h2>
        </div>
    </div>

    {{-- Datos generales --}}
    <div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm p-7">
        <p class="text-xs font-semibold text-sepia-400 uppercase tracking-wider mb-5">Datos del usuario</p>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PUT')
            @include('admin.users._form')
            <div class="flex items-center gap-3 mt-7 pt-6 border-t border-parchment-300">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar cambios
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-ghost">Cancelar</a>
            </div>
        </form>
    </div>

    {{-- Cambiar contraseña --}}
    <div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm p-7">
        <p class="text-xs font-semibold text-sepia-400 uppercase tracking-wider mb-5">Cambiar contraseña</p>
        <form method="POST" action="{{ route('admin.users.change-password', $user) }}">
            @csrf @method('PATCH')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Nueva contraseña <span class="text-red-500 normal-case">*</span></label>
                    <input type="password" name="password" autocomplete="new-password"
                           class="form-input {{ $errors->has('password') ? 'border-red-300 bg-red-50' : '' }}">
                    @error('password') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" class="form-input">
                </div>
            </div>
            <div class="mt-5">
                <button type="submit" class="btn-secondary">
                    Actualizar contraseña
                </button>
            </div>
        </form>
    </div>

</div>

@endsection
