<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::with('category', 'authors')->latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $authors    = Author::orderBy('last_name')->get();
        return view('admin.books.create', compact('categories', 'authors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'isbn'         => ['nullable', 'string', 'max:20', 'unique:books'],
            'summary'      => ['nullable', 'string'],
            'publisher'    => ['nullable', 'string', 'max:150'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'published_at' => ['nullable', 'date'],
            'book_cover'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=300,min_height=400,max_width=400,max_height=500'],
            'authors'      => ['nullable', 'array'],
            'authors.*'    => ['exists:authors,id'],
        ]);

        if ($request->hasFile('book_cover')) {
            $data['book_cover'] = $request->file('book_cover')->store('books', 'public');
        }

        $authorIds = $data['authors'] ?? [];
        unset($data['authors']);

        $book = Book::create($data);
        $book->authors()->sync($authorIds);

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro creado correctamente.');
    }

    public function edit(Book $book): View
    {
        $categories = Category::orderBy('name')->get();
        $authors    = Author::orderBy('last_name')->get();
        return view('admin.books.edit', compact('book', 'categories', 'authors'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'isbn'         => ['nullable', 'string', 'max:20', 'unique:books,isbn,' . $book->id],
            'summary'      => ['nullable', 'string'],
            'publisher'    => ['nullable', 'string', 'max:150'],
            'category_id'  => ['nullable', 'exists:categories,id'],
            'published_at' => ['nullable', 'date'],
            'book_cover'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:min_width=300,min_height=400,max_width=400,max_height=500'],
            'authors'      => ['nullable', 'array'],
            'authors.*'    => ['exists:authors,id'],
        ]);

        if ($request->hasFile('book_cover')) {
            if ($book->book_cover) {
                Storage::disk('public')->delete($book->book_cover);
            }
            $data['book_cover'] = $request->file('book_cover')->store('books', 'public');
        }

        $authorIds = $data['authors'] ?? [];
        unset($data['authors']);

        $book->update($data);
        $book->authors()->sync($authorIds);

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro actualizado correctamente.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        if ($book->book_cover) {
            Storage::disk('public')->delete($book->book_cover);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Libro eliminado.');
    }
}
