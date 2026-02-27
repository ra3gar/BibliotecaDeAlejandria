@php $isEdit = isset($book); @endphp

<div class="space-y-4">

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Título <span class="text-red-500">*</span></label>
        <input type="text" name="title"
               value="{{ old('title', $book->title ?? '') }}"
               class="w-full border rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('title') border-red-400 bg-red-50 @else border-gray-300 @enderror">
        @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
            <input type="text" name="isbn"
                   value="{{ old('isbn', $book->isbn ?? '') }}"
                   placeholder="978-0-000-00000-0"
                   class="w-full border rounded-lg px-3 py-2.5 text-sm font-mono text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 @error('isbn') border-red-400 bg-red-50 @else border-gray-300 @enderror">
            @error('isbn') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Editorial</label>
            <input type="text" name="publisher"
                   value="{{ old('publisher', $book->publisher ?? '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Categoría</label>
            <select name="category_id"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
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
            <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de publicación</label>
            <input type="date" name="published_at"
                   value="{{ old('published_at', isset($book) && $book->published_at ? $book->published_at->format('Y-m-d') : '') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Resumen</label>
        <textarea name="summary" rows="4"
                  class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500 resize-none">{{ old('summary', $book->summary ?? '') }}</textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Autores</label>
        <select name="authors[]" multiple
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-500"
                size="5">
            @php $selectedAuthors = old('authors', isset($book) ? $book->authors->pluck('id')->toArray() : []); @endphp
            @foreach($authors as $author)
            <option value="{{ $author->id }}" {{ in_array($author->id, $selectedAuthors) ? 'selected' : '' }}>
                {{ $author->full_name }}
            </option>
            @endforeach
        </select>
        <p class="mt-1 text-xs text-gray-400">Mantén Ctrl/Cmd para seleccionar varios.</p>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Portada (book_cover)</label>
        @if($isEdit && $book->book_cover)
        <div class="mb-2 flex items-center gap-3">
            <img src="{{ Storage::url($book->book_cover) }}" alt="Portada actual" class="w-14 h-20 object-cover rounded shadow-sm">
            <p class="text-xs text-gray-400">Portada actual. Sube una nueva para reemplazarla.</p>
        </div>
        @endif
        <input type="file" name="book_cover" accept="image/*"
               class="w-full text-sm text-gray-700 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
        @error('book_cover') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        <p class="mt-1 text-xs text-gray-400">JPG, PNG o WebP. Máximo 2 MB.</p>
    </div>

</div>
