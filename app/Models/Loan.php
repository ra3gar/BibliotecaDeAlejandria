<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'loan_date',
        'return_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'loan_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // ── Query Scopes ─────────────────────────────────────────────────────────

    public function scopeFilterUser(Builder $query, ?string $userId): Builder
    {
        return $userId ? $query->where('user_id', $userId) : $query;
    }

    public function scopeFilterBook(Builder $query, ?string $title): Builder
    {
        return $title
            ? $query->whereHas('book', fn (Builder $q) => $q->where('title', 'like', "%{$title}%"))
            : $query;
    }

    public function scopeFilterCategory(Builder $query, ?string $categoryId): Builder
    {
        return $categoryId
            ? $query->whereHas('book', fn (Builder $q) => $q->where('category_id', $categoryId))
            : $query;
    }

    public function scopeFilterAuthor(Builder $query, ?string $authorId): Builder
    {
        return $authorId
            ? $query->whereHas('book.authors', fn (Builder $q) => $q->where('authors.id', $authorId))
            : $query;
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        return $status ? $query->where('status', $status) : $query;
    }
}
