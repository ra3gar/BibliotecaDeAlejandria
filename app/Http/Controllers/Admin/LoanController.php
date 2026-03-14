<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function index(Request $request): View
    {
        $loans = Loan::with(['user', 'book.category', 'book.authors'])
            ->filterUser($request->user_id)
            ->filterBook($request->book)
            ->filterCategory($request->category_id)
            ->filterAuthor($request->author_id)
            ->filterStatus($request->status)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $users      = User::where('role', 'user')->orderBy('last_name')->get();
        $categories = Category::orderBy('name')->get();
        $authors    = Author::orderBy('last_name')->get();

        return view('admin.loans.index', compact('loans', 'users', 'categories', 'authors'));
    }

    public function create(): View
    {
        $users = User::where('role', 'user')->orderBy('last_name')->get();
        $books = Book::where('available_copies', '>', 0)->orderBy('title')->get();

        return view('admin.loans.create', compact('users', 'books'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id'   => ['required', 'exists:book_store_users,id'],
            'book_id'   => ['required', 'exists:books,id'],
            'loan_date' => ['required', 'date'],
        ]);

        $book = Book::findOrFail($data['book_id']);

        if (! $book->isAvailable()) {
            return back()->withErrors(['book_id' => 'No hay copias disponibles de este libro.'])->withInput();
        }

        Loan::create([
            'user_id'   => $data['user_id'],
            'book_id'   => $data['book_id'],
            'loan_date' => $data['loan_date'],
            'status'    => 'active',
        ]);

        $book->decrement('available_copies');

        return redirect()->route('admin.loans.index')
            ->with('success', 'Préstamo registrado correctamente.');
    }

    public function show(Loan $loan): View
    {
        $loan->load(['user', 'book.category', 'book.authors']);

        return view('admin.loans.show', compact('loan'));
    }

    public function markReturned(Loan $loan): RedirectResponse
    {
        if ($loan->status === 'returned') {
            return redirect()->route('admin.loans.index')
                ->with('error', 'Este préstamo ya fue devuelto.');
        }

        $loan->update([
            'status'      => 'returned',
            'return_date' => now()->toDateString(),
        ]);

        $loan->book->increment('available_copies');

        return redirect()->route('admin.loans.index')
            ->with('success', 'Préstamo marcado como devuelto.');
    }

    public function destroy(Loan $loan): RedirectResponse
    {
        $loan->delete();

        return redirect()->route('admin.loans.index')
            ->with('success', 'Préstamo eliminado correctamente.');
    }
}
