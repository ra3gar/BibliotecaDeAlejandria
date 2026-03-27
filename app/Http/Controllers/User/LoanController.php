<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\ReservaConfirmadaMail;
use App\Models\AuditLog;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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

        $loan = Loan::create([
            'user_id'   => $user->id,
            'book_id'   => $book->id,
            'loan_date' => now()->toDateString(),
            'status'    => 'pending',
            'qr_token'  => Str::uuid()->toString(),
        ]);

        $book->decrement('available_copies');

        // Cargar relaciones necesarias para el email antes de encolar
        $loan->load(['user', 'book.authors']);

        // Enviar email de confirmación con QR (encolado para no bloquear la respuesta)
        Mail::to($user->email)->queue(new ReservaConfirmadaMail($loan));

        return redirect()->route('profile')
            ->with('success', 'Reserva realizada. Recibirás un correo con tu QR para recoger el libro.');
    }

    public function cancel(Loan $loan): RedirectResponse
    {
        $user = auth()->user();

        // Verificar que el préstamo pertenece al usuario autenticado
        if ($loan->user_id !== $user->id) {
            abort(403);
        }

        // Solo se pueden cancelar reservas pendientes
        if ($loan->status !== 'pending') {
            return back()->with('error', 'Solo puedes cancelar reservas que aún no han sido confirmadas.');
        }

        $loan->load('book');

        // Restaurar el stock
        $loan->book->increment('available_copies');

        // Registrar en auditoría
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'deleted',
            'model_type'  => 'Loan',
            'model_id'    => $loan->id,
            'description' => "Reserva cancelada por el usuario: \"{$loan->book->title}\" (préstamo #{$loan->id})",
        ]);

        $loan->delete();

        return redirect()->route('profile')
            ->with('success', 'Reserva cancelada. El libro está nuevamente disponible.');
    }
}
