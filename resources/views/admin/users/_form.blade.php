@php $isEdit = isset($user); @endphp

<div class="space-y-5">

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Nombre</label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', $user->first_name ?? '') }}"
                   class="form-input {{ $errors->has('first_name') ? 'border-red-300 bg-red-50' : '' }}">
            @error('first_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label">Apellido</label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', $user->last_name ?? '') }}"
                   class="form-input {{ $errors->has('last_name') ? 'border-red-300 bg-red-50' : '' }}">
            @error('last_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="email"
               value="{{ old('email', $user->email ?? '') }}"
               class="form-input {{ $errors->has('email') ? 'border-red-300 bg-red-50' : '' }}">
        @error('email') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="form-label">Fecha de nacimiento</label>
        <input type="date" name="birth_date"
               value="{{ old('birth_date', isset($user) && $user->birth_date ? $user->birth_date->format('Y-m-d') : '') }}"
               class="form-input {{ $errors->has('birth_date') ? 'border-red-300 bg-red-50' : '' }}">
        @error('birth_date') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1.5 text-xs text-sepia-400">Requerida para validar restricciones de edad en préstamos.</p>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Rol</label>
            <select name="role"
                    class="form-input {{ $errors->has('role') ? 'border-red-300' : '' }}">
                <option value="user"  {{ old('role', $user->role ?? 'user')  === 'user'  ? 'selected' : '' }}>Usuario</option>
                <option value="admin" {{ old('role', $user->role ?? '')      === 'admin' ? 'selected' : '' }}>Administrador</option>
            </select>
            @error('role') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label">Estado de cuenta</label>
            <label class="flex items-center gap-3 mt-2">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}
                       class="w-5 h-5 rounded border-parchment-400 accent-gold-500">
                <span class="text-sm text-sepia-600">Cuenta activa</span>
            </label>
        </div>
    </div>

    {{-- Contraseña solo en creación --}}
    @if(! $isEdit)
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Contraseña <span class="text-red-500 normal-case">*</span></label>
            <input type="password" name="password" autocomplete="new-password"
                   class="form-input {{ $errors->has('password') ? 'border-red-300 bg-red-50' : '' }}">
            @error('password') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="password_confirmation" class="form-input">
        </div>
    </div>
    @endif

</div>
