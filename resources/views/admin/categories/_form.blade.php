<div class="space-y-4">

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Nombre <span class="text-red-500">*</span></label>
        <input type="text" name="name"
               value="{{ old('name', $category->name ?? '') }}"
               class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                      focus:outline-none focus:ring-2 focus:ring-gold-500
                      @error('name') border-red-400 bg-red-50 @else border-parchment-400 @enderror">
        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Descripción</label>
        <textarea name="description" rows="4"
                  class="w-full border border-parchment-400 rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                         focus:outline-none focus:ring-2 focus:ring-gold-500 resize-none">{{ old('description', $category->description ?? '') }}</textarea>
    </div>

</div>
