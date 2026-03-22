<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class LoanController extends Controller
{
    public function store(Book $book): RedirectResponse
    {
        $user = auth()->user();

        if (! $book->isAvailable()) {
            return back()->with('error', 'No hay copias disponibles de este libro.');
        }

        if ($book->min_age > 0 && $user->age() !== null && $user->age() < $book->min_age) {
            return back()->with('error', "Debes tener al menos {$book->min_age} años para solicitar este libro.");
        }

        // Prevent duplicate active/pending reservations for same book
        $existing = Loan::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['pending', 'active'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'Ya tienes una reserva activa o pendiente para este libro.');
        }

        Loan::create([
            'user_id'   => $user->id,
            'book_id'   => $book->id,
            'loan_date' => now()->toDateString(),
            'status'    => 'pending',
            'qr_token'  => Str::uuid()->toString(),
        ]);

        $book->decrement('available_copies');

        return redirect()->route('profile')
            ->with('success', 'Reserva realizada. Presenta tu QR en la biblioteca para recoger el libro.');
    }
}
