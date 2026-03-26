<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'steps',
        'image',
        'status',
        'servings',
        'nutritional_info',
    ];

    protected function casts(): array
    {
        return [
            'steps'            => 'array',
            'prep_time'        => 'integer',
            'cook_time'        => 'integer',
            'likes_count'      => 'integer',
            'status'           => 'string',
            'servings'         => 'integer',
            'nutritional_info' => 'array',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function likedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withPivot('created_at');;
    }

    public function favouritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favourites')->withPivot('created_at');
    }

    public function ingredients(): HasMany
    {
        return $this->hasMany(Ingredient::class)->orderBy('order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(RecipeImage::class)->orderBy('order');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_recipe')
                    ->withPivot('order')
                    ->withTimestamps();
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
