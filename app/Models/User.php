<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────────────

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function likes()
    {
        return $this->belongsToMany(Recipe::class, 'likes')->withPivot('created_at');
    }

    public function favourites()
    {
        return $this->belongsToMany(Recipe::class, 'favourites')->withPivot('created_at');
    }
}
