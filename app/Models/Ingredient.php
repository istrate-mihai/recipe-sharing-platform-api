<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
