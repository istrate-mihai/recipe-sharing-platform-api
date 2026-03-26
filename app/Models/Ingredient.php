<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model
{
    protected $fillable = [
        'id',
        'recipe_id',
        'quantity',
        'unit',
        'name',
        'order',
    ];

    protected $casts = [
        'quantity' => 'float',
        'order'    => 'integer',
    ];

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
