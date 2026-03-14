<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'isbn',
        'codigo_interno',
        'summary',
        'publisher',
        'category_id',
        'book_cover',
        'path_pdf',
        'published_at',
        'año',
        'stock_total',
        'available_copies',
    ];

    protected function casts(): array
    {
        return [
            'published_at'     => 'date',
            'año'              => 'integer',
            'stock_total'      => 'integer',
            'available_copies' => 'integer',
        ];
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'book_author');
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
