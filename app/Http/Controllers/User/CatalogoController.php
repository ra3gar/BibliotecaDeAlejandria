<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\View\View;

class CatalogoController extends Controller
{
    public function index(): View
    {
        return view('user.catalogo', [
            'latestBooks' => Book::with('authors')->latest()->take(8)->get(),
            'categories'  => Category::withCount('books')->get(),
            'authors'     => Author::withCount('books')->get(),
        ]);
    }

    public function byCategory(Category $category): View
    {
        $books = $category->books()->with('authors', 'category')->get();

        return view('user.catalogo-filtrado', [
            'books'      => $books,
            'pageTitle'  => 'Libros de ' . $category->name,
            'categories' => Category::withCount('books')->get(),
            'authors'    => Author::withCount('books')->get(),
        ]);
    }

    public function byAuthor(Author $author): View
    {
        $author->load('books.category', 'books.authors');

        return view('user.autor-detalle', compact('author'));
    }

    public function show(Book $book): View
    {
        $book->load('category', 'authors');

        // Verificar si el usuario ya tiene una reserva activa o pendiente de este libro
        $userLoan = auth()->user()->loans()
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'active'])
            ->first();

        return view('user.book-detail', compact('book', 'userLoan'));
    }
}
