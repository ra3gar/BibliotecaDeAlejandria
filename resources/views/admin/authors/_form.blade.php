<div class="space-y-5">

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="form-label">Nombre <span class="text-red-500 normal-case">*</span></label>
            <input type="text" name="first_name"
                   value="{{ old('first_name', $author->first_name ?? '') }}"
                   class="form-input {{ $errors->has('first_name') ? 'border-red-300 bg-red-50' : '' }}">
            @error('first_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="form-label">Apellido <span class="text-red-500 normal-case">*</span></label>
            <input type="text" name="last_name"
                   value="{{ old('last_name', $author->last_name ?? '') }}"
                   class="form-input {{ $errors->has('last_name') ? 'border-red-300 bg-red-50' : '' }}">
            @error('last_name') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Fotografía --}}
    <div>
        <label class="form-label">Fotografía</label>
        @if(isset($author) && $author->photo_url)
            <div class="mb-3 flex items-center gap-3">
                <img src="{{ $author->photo_url }}"
                     alt="{{ $author->full_name }}"
                     class="w-16 h-16 rounded-full object-cover border-2 border-parchment-300">
                <span class="text-xs text-sepia-400">Foto actual. Sube una nueva para reemplazarla.</span>
            </div>
        @endif
        <input type="file" name="photo" accept="image/jpeg,image/jpg,image/png"
               class="w-full text-sm text-sepia-600 border border-parchment-300 rounded-xl px-3 py-2
                      file:mr-4 file:py-1.5 file:px-4 file:rounded-lg file:border-0
                      file:text-xs file:font-semibold file:bg-gold-500/10 file:text-gold-700
                      hover:file:bg-gold-500/20 transition-all
                      {{ $errors->has('photo') ? 'border-red-300 bg-red-50' : '' }}">
        <p class="mt-1.5 text-xs text-sepia-400">JPEG o PNG · máx. 2 MB</p>
        @error('photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Biografía --}}
    <div>
        <label class="form-label">Biografía</label>
        <textarea name="bio" rows="5"
                  class="form-input resize-none">{{ old('bio', $author->bio ?? '') }}</textarea>
    </div>

</div>
