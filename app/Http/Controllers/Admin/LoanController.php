<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
        $user = User::findOrFail($data['user_id']);

        if (! $book->isAvailable()) {
            return back()->withErrors(['book_id' => 'No hay copias disponibles de este libro.'])->withInput();
        }

        if ($book->min_age > 0 && $user->age() !== null && $user->age() < $book->min_age) {
            return back()->withErrors([
                'user_id' => "El usuario debe tener al menos {$book->min_age} años para solicitar este libro.",
            ])->withInput();
        }

        Loan::create([
            'user_id'   => $data['user_id'],
            'book_id'   => $data['book_id'],
            'loan_date' => $data['loan_date'],
            'status'    => 'pending',
            'qr_token'  => Str::uuid()->toString(),
        ]);

        $book->decrement('available_copies');

        return redirect()->route('admin.loans.index')
            ->with('success', 'Reserva registrada. Escanee el QR al entregar el libro.');
    }

    public function show(Loan $loan): View
    {
        $loan->load(['user', 'book.category', 'book.authors']);

        return view('admin.loans.show', compact('loan'));
    }

    public function confirmPickup(Loan $loan): RedirectResponse
    {
        if ($loan->status !== 'pending') {
            return redirect()->route('admin.loans.show', $loan)
                ->with('error', 'Este préstamo no está en estado pendiente.');
        }

        $loan->load(['book', 'user']);
        $loan->update(['status' => 'active']);

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'updated',
            'model_type'  => 'Loan',
            'model_id'    => $loan->id,
            'description' => "Entrega confirmada del préstamo #{$loan->id} — \"{$loan->book->title}\" a {$loan->user->full_name}",
        ]);

        return redirect()->route('admin.loans.show', $loan)
            ->with('success', 'Entrega confirmada. El préstamo está ahora activo.');
    }

    public function markReturned(Loan $loan): RedirectResponse
    {
        if ($loan->status === 'returned') {
            return redirect()->route('admin.loans.index')
                ->with('error', 'Este préstamo ya fue devuelto.');
        }

        $loan->load(['book', 'user']);
        $loan->update([
            'status'      => 'returned',
            'return_date' => now()->toDateString(),
        ]);

        $loan->book->increment('available_copies');

        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'updated',
            'model_type'  => 'Loan',
            'model_id'    => $loan->id,
            'description' => "Devolución registrada del préstamo #{$loan->id} — \"{$loan->book->title}\"",
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
