<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
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

    public function show(Loan $loan): View
    {
        $loan->load(['user', 'book.category', 'book.authors']);

        return view('admin.loans.show', compact('loan'));
    }

    public function markReturned(Loan $loan): RedirectResponse
    {
        $loan->update([
            'status'      => 'returned',
            'return_date' => now()->toDateString(),
        ]);

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
