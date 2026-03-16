@php $isEdit = isset($user); @endphp

<div class="space-y-4">

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Nombre</label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', $user->first_name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500
                          {{ $errors->has('first_name') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
            @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Apellido</label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', $user->last_name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500
                          {{ $errors->has('last_name') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
            @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Email</label>
        <input type="email" name="email"
               value="{{ old('email', $user->email ?? '') }}"
               class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                      focus:outline-none focus:ring-2 focus:ring-gold-500
                      {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Rol</label>
            <select name="role"
                    class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                           focus:outline-none focus:ring-2 focus:ring-gold-500
                           {{ $errors->has('role') ? 'border-red-400' : 'border-parchment-400' }}">
                <option value="user"  {{ old('role', $user->role ?? 'user')  === 'user'  ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ old('role', $user->role ?? '')      === 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
            @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Estado de cuenta</label>
            <label class="flex items-center gap-3 h-10.5">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-parchment-400 text-gold-500 focus:ring-gold-500">
                <span class="text-sm text-sepia-600">Cuenta activa</span>
            </label>
        </div>
    </div>

    {{-- Contraseña solo en creación --}}
    @if(! $isEdit)
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Contraseña <span class="text-red-500">*</span></label>
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
    @endif

</div>
