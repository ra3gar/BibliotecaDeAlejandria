<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

class Author extends Model
{
    protected $fillable = ['first_name', 'last_name', 'bio', 'photo_path'];

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path
            ? Storage::disk('public')->url($this->photo_path)
            : null;
    }

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_author');
    }
}
