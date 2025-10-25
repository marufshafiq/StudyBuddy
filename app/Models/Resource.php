<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'authors',
        'openlibrary_key',
        'cover_url',
        'isbn',
        'first_publish_year',
        'subjects',
        'url',
        'read_url',
        'has_fulltext',
        'is_favorite',
        'notes',
    ];

    protected $casts = [
        'authors' => 'array',
        'subjects' => 'array',
        'is_favorite' => 'boolean',
        'has_fulltext' => 'boolean',
    ];

    /**
     * Get the user that owns the resource.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for favorite resources
     */
    public function scopeFavorites($query)
    {
        return $query->where('is_favorite', true);
    }

    /**
     * Scope for books
     */
    public function scopeBooks($query)
    {
        return $query->where('type', 'book');
    }

    /**
     * Get formatted author names
     */
    public function getAuthorsStringAttribute(): string
    {
        if (empty($this->authors)) {
            return 'Unknown Author';
        }
        
        return implode(', ', array_slice($this->authors, 0, 3));
    }

    /**
     * Get formatted subjects
     */
    public function getSubjectsStringAttribute(): string
    {
        if (empty($this->subjects)) {
            return '';
        }
        
        return implode(', ', array_slice($this->subjects, 0, 5));
    }
}
