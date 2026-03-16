<div class="space-y-4">

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Nombre <span class="text-red-500">*</span></label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', $author->first_name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500
                          {{ $errors->has('first_name') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
            @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Apellido <span class="text-red-500">*</span></label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', $author->last_name ?? '') }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500
                          {{ $errors->has('last_name') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
            @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Fotografía --}}
    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Fotografía</label>

        @if(isset($author) && $author->photo_url)
            <div class="mb-2 flex items-center gap-3">
                <img src="{{ $author->photo_url }}"
                     alt="{{ $author->full_name }}"
                     class="w-16 h-16 rounded-full object-cover border-2 border-parchment-300">
                <span class="text-xs text-sepia-400">Foto actual. Sube una nueva para reemplazarla.</span>
            </div>
        @endif

        <input type="file" name="photo" accept="image/jpeg,image/jpg,image/png"
               class="block w-full text-sm text-sepia-600 border rounded-lg
                      file:mr-3 file:py-2 file:px-4 file:rounded-l-lg file:border-0
                      file:text-sm file:font-medium file:bg-gold-500/10 file:text-gold-700
                      hover:file:bg-gold-500/20
                      {{ $errors->has('photo') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
        <p class="mt-1 text-xs text-sepia-400">JPEG o PNG · máx. 2 MB</p>
        @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Biografía --}}
    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Biografía</label>
        <textarea name="bio" rows="5"
                  class="w-full border border-parchment-400 rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                         focus:outline-none focus:ring-2 focus:ring-gold-500 resize-none">{{ old('bio', $author->bio ?? '') }}</textarea>
    </div>

</div>
