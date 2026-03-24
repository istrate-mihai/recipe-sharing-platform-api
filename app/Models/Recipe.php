<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'difficulty',
        'prep_time',
        'cook_time',
        'ingredients',
        'steps',
        'image',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'ingredients' => 'array',
            'steps'       => 'array',
            'prep_time'   => 'integer',
            'cook_time'   => 'integer',
            'likes_count' => 'integer',
            'status'      => 'string',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likedBy()
    {
        return $this->belongsToMany(User::class, 'likes')->withPivot('created_at');;
    }

    public function favouritedBy()
    {
        return $this->belongsToMany(User::class, 'favourites')->withPivot('created_at');
    }

    // ── Scopes ───────────────────────────────────────────────────────────────

    public function scopeSearch($query, ?string $term)
    {
        if (! $term) return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopeCategory($query, ?string $category)
    {
        if (! $category) return $query;
        return $query->where('category', $category);
    }

    public function scopeDifficulty($query, ?string $difficulty)
    {
        if (! $difficulty) return $query;
        return $query->where('difficulty', $difficulty);
    }

    public function scopeVisible(Builder $query): void
    {
        $query->where('status', 'published');
    }

    public function scopeVisibleTo(Builder $query, int $userId): void
    {
        $query->where(function ($q) use ($userId) {
            $q->where('status', 'published')
            ->orWhere(function ($q2) use ($userId) {
                $q2->where('user_id', $userId)
                    ->whereIn('status', ['draft', 'private']);
            });
        });
    }
}
