<?php

namespace App\Observers;

use App\Models\AuditLog;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class BookObserver
{
    public function created(Book $book): void
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'created',
            'model_type'  => 'Book',
            'model_id'    => $book->id,
            'description' => "Libro creado: \"{$book->title}\"",
        ]);
    }

    public function updated(Book $book): void
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'updated',
            'model_type'  => 'Book',
            'model_id'    => $book->id,
            'description' => "Libro actualizado: \"{$book->title}\"",
        ]);
    }

    public function deleted(Book $book): void
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'deleted',
            'model_type'  => 'Book',
            'model_id'    => $book->id,
            'description' => "Libro eliminado: \"{$book->title}\"",
        ]);
    }
}
