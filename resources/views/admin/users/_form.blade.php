@php $isEdit = isset($user); @endphp

<div class="space-y-4">

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', $user->first_name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('first_name') border-red-400 bg-red-50 @else border-gray-300 @enderror">
            @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido</label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', $user->last_name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('last_name') border-red-400 bg-red-50 @else border-gray-300 @enderror">
            @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input type="email" name="email"
               value="{{ old('email', $user->email ?? '') }}"
               class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('email') border-red-400 bg-red-50 @else border-gray-300 @enderror">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Contraseña {{ $isEdit ? '(dejar vacío para no cambiar)' : '' }}
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

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Rol</label>
        <select name="role"
                class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('role') border-red-400 @else border-gray-300 @enderror">
            <option value="user"  {{ old('role', $user->role ?? 'user')  === 'user'  ? 'selected' : '' }}>Usuario</option>
            <option value="admin" {{ old('role', $user->role ?? '')      === 'admin' ? 'selected' : '' }}>Administrador</option>
        </select>
        @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

</div>
