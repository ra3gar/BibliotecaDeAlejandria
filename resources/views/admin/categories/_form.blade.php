<div class="space-y-5">

    <div>
        <label class="form-label">Nombre <span class="text-red-500 normal-case">*</span></label>
        <input type="text" name="name"
               value="{{ old('name', $category->name ?? '') }}"
               class="form-input {{ $errors->has('name') ? 'border-red-300 bg-red-50' : '' }}">
        @error('name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="form-label">Descripción</label>
        <textarea name="description" rows="4"
                  class="form-input resize-none">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

</div>
