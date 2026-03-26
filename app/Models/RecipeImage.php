<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class RecipeImage extends Model
{
    protected $fillable = ['recipe_id', 'path', 'order', 'is_primary'];

    protected $casts = [
        'is_primary' => 'boolean',
        'order'      => 'integer',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('s3')->url($this->path);
    }
}
