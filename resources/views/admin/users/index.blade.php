@extends('layouts.admin')

@section('title', 'Usuarios')

@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
    {{ session('error') }}
</div>
@endif

<div class="flex items-center justify-between mb-5">
    <div>
        <h2 class="text-base font-semibold text-gray-700">Gestión de Usuarios</h2>
        <p class="text-sm text-gray-400">{{ $users->total() }} usuarios registrados</p>
    </div>
    <a href="{{ route('admin.users.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-900 font-semibold rounded-lg text-sm transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nuevo Usuario
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nombre</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Rol</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Estado</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Registrado</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50 transition {{ ! $user->is_active ? 'opacity-60' : '' }}">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center text-amber-400 font-bold text-xs flex-shrink-0">
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $user->full_name }}</span>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                 {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
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
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                            Inactivo
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-400 text-xs">{{ $user->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-end gap-2 flex-wrap">
                        <a href="{{ route('admin.users.edit', $user) }}"
                           class="px-3 py-1 text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                            Editar
                        </a>

                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="px-3 py-1 text-xs font-medium rounded-lg transition
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
                                    class="px-3 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                Eliminar
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400 text-sm">
                    No hay usuarios registrados.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
    <div class="px-4 py-3 border-t border-gray-100">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection
