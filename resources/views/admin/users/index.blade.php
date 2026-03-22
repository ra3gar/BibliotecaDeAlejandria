@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')

@if(session('success'))
<div class="mb-5 px-4 py-3 bg-parchment-50 border border-gold-400/50 rounded-xl text-sm text-sepia-700 flex items-center gap-3 lib-animate">
    <span class="flex-shrink-0 w-7 h-7 rounded-full bg-gold-500/15 flex items-center justify-center">
        <svg class="w-4 h-4 text-gold-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
    </span>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 flex items-center gap-3 lib-animate">
    <span class="flex-shrink-0 w-7 h-7 rounded-full bg-red-100 flex items-center justify-center">
        <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
    </span>
    {{ session('error') }}
</div>
@endif

<div class="flex items-center justify-between mb-5 lib-animate">
    <div>
        <h2 class="text-base font-serif font-semibold text-mahogany-900">Gestión de Usuarios</h2>
        <p class="text-sm text-sepia-400">{{ $users->total() }} usuarios registrados</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="btn-primary">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Usuario
    </a>
</div>

<div class="bg-parchment-50 border border-parchment-300 rounded-2xl shadow-sm overflow-hidden lib-animate">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-parchment-100 border-b border-parchment-400">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Nombre</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Email</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Rol</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Estado</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-sepia-500 uppercase tracking-wide">Registrado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-parchment-300">
            @forelse($users as $user)
            <tr class="hover:bg-parchment-100 transition-colors duration-150 {{ ! $user->is_active ? 'opacity-60' : '' }}">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-mahogany-900 flex items-center justify-center text-gold-400 font-bold text-xs shrink-0">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-mahogany-900">{{ $user->full_name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-sepia-600">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                 {{ $user->role === 'admin' ? 'bg-midnight-100 text-midnight-800' : 'bg-parchment-200 text-sepia-600' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    @if($user->is_active)
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Activo
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-parchment-200 text-sepia-500">
                            <span class="w-1.5 h-1.5 rounded-full bg-sepia-400"></span>
                            Inactivo
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-sepia-400 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2 flex-wrap">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="px-3 py-1 text-xs font-medium text-midnight-800 bg-midnight-100 hover:bg-midnight-100/70 rounded-xl transition-colors duration-150">
                            Editar
                        </a>

                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium rounded-xl transition-colors duration-150
                                           {{ $user->is_active
                                               ? 'text-orange-700 bg-orange-50 hover:bg-orange-100'
                                               : 'text-green-700 bg-green-50 hover:bg-green-100' }}">
                                {{ $user->is_active ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('¿Eliminar a {{ $user->full_name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-xl transition-colors duration-150">
                                Eliminar
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-sepia-400 text-sm">
                    No hay usuarios registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>

    @if($users->hasPages())
    <div class="px-4 py-3 border-t border-parchment-300">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
