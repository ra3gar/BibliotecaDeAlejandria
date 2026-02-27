<div class="space-y-4">

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', $author->first_name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('first_name') border-red-400 bg-red-50 @else border-gray-300 @enderror">
            @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Apellido <span class="text-red-500">*</span></label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', $author->last_name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('last_name') border-red-400 bg-red-50 @else border-gray-300 @enderror">
            @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Biografía</label>
        <textarea name="bio" rows="5"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 resize-none">{{ old('bio', $author->bio ?? '') }}</textarea>
    </div>

</div>
