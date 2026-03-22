@php $isEdit = isset($book); @endphp

<div class="space-y-4">

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Título <span class="text-red-500">*</span></label>
        <input type="text" name="title"
               value="{{ old('title', $book->title ?? '') }}"
               class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                      focus:outline-none focus:ring-2 focus:ring-gold-500
                      {{ $errors->has('title') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">ISBN</label>
            <input type="text" name="isbn"
                   value="{{ old('isbn', $book->isbn ?? '') }}"
                   placeholder="978-0-000-00000-0"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm font-mono text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500
                          {{ $errors->has('isbn') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
            @error('isbn') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Editorial</label>
            <input type="text" name="publisher"
                   value="{{ old('publisher', $book->publisher ?? '') }}"
                   class="w-full border border-parchment-400 rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500">
        </div>
    </div>

    {{-- Fecha de publicación + Año (derivado automáticamente) --}}
    <div x-data="{ publishedAt: '{{ old('published_at', isset($book) && $book->published_at ? $book->published_at->format('Y-m-d') : '') }}' }"
         class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Fecha de publicación</label>
            <input type="date" name="published_at"
                   x-model="publishedAt"
                   class="w-full border border-parchment-400 rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">Año de publicación</label>
            <input type="text" readonly
                   :value="publishedAt ? new Date(publishedAt + 'T00:00:00').getFullYear() : ''"
                   placeholder="Se calcula automáticamente"
                   class="w-full border border-parchment-300 rounded-lg px-3 py-2.5 text-sm text-sepia-500
                          bg-parchment-200 cursor-not-allowed">
            <p class="mt-1 text-xs text-sepia-400">Se calcula a partir de la fecha de publicación.</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Edad mínima requerida</label>
        <input type="number" name="min_age" min="0" max="120"
               value="{{ old('min_age', $book->min_age ?? 0) }}"
               class="w-full border border-parchment-400 rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                      focus:outline-none focus:ring-2 focus:ring-gold-500">
        @error('min_age') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-sepia-400">0 = sin restricción de edad.</p>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">
                Ejemplares totales <span class="text-red-500">*</span>
            </label>
            <input type="number" name="stock_total" min="0"
                   value="{{ old('stock_total', $book->stock_total ?? 0) }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500
                          {{ $errors->has('stock_total') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
            @error('stock_total') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            <p class="mt-1 text-xs text-sepia-400">Total de copias físicas en la biblioteca.</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-sepia-600 mb-1">
                Copias disponibles <span class="text-red-500">*</span>
            </label>
            <input type="number" name="available_copies" min="0"
                   value="{{ old('available_copies', $book->available_copies ?? 0) }}"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                          focus:outline-none focus:ring-2 focus:ring-gold-500
                          {{ $errors->has('available_copies') ? 'border-red-400 bg-red-50' : 'border-parchment-400' }}">
            @error('available_copies') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            <p class="mt-1 text-xs text-sepia-400">No puede superar el total de ejemplares.</p>
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Categoría</label>
        <select name="category_id"
                class="w-full border border-parchment-400 rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                       focus:outline-none focus:ring-2 focus:ring-gold-500">
            <option value="">— Sin categoría —</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}"
                {{ old('category_id', $book->category_id ?? '') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Resumen</label>
        <textarea name="summary" rows="4"
                  class="w-full border border-parchment-400 rounded-lg px-3 py-2.5 text-sm text-mahogany-900 bg-parchment-50
                         focus:outline-none focus:ring-2 focus:ring-gold-500 resize-none">{{ old('summary', $book->summary ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Autores</label>
        <select name="authors[]" multiple
                class="w-full border border-parchment-400 rounded-lg px-3 py-2 text-sm text-mahogany-900 bg-parchment-50
                       focus:outline-none focus:ring-2 focus:ring-gold-500"
                size="5">
            @php $selectedAuthors = old('authors', isset($book) ? $book->authors->pluck('id')->toArray() : []); @endphp
            @foreach($authors as $author)
            <option value="{{ $author->id }}" {{ in_array($author->id, $selectedAuthors) ? 'selected' : '' }}>
                {{ $author->full_name }}
            </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-sepia-400">Mantén Ctrl/Cmd para seleccionar varios.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-sepia-600 mb-1">Portada (book_cover)</label>
        @if($isEdit && $book->book_cover)
        <div class="mb-2 flex items-center gap-3">
            <img src="{{ Storage::url($book->book_cover) }}" alt="Portada actual"
                 class="w-14 h-20 object-contain rounded shadow-sm border border-parchment-300">
            <p class="text-xs text-sepia-400">Portada actual. Sube una nueva para reemplazarla.</p>
        </div>
        @endif
        <input type="file" name="book_cover" accept="image/*"
               class="w-full text-sm text-sepia-600
                      file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                      file:text-sm file:font-medium file:bg-gold-500/10 file:text-gold-700
                      hover:file:bg-gold-500/20">
        @error('book_cover') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-sepia-400">JPG, PNG o WebP. Máximo 2 MB. Dimensiones: mínimo 300×400 px, máximo 400×500 px.</p>
    </div>

</div>
