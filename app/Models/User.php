<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'bio',
        'stripe_customer_id',
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

    public function recipes(): HasMany
    {
        return $this->hasMany(Recipe::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'likes')->withPivot('created_at');
    }

    public function favourites(): BelongsToMany
    {
        return $this->belongsToMany(Recipe::class, 'favourites')->withPivot('created_at');
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    public function isPremium(): bool
    {
        return $this->subscription?->isActive() ?? false;
    }

    public function plan(): string
    {
        return $this->isPremium() ? 'premium' : 'free';
    }

    public function remainingFreeRecipes(): ?int
    {
        if ($this->isPremium()) {
            return null;
        }

        $used = $this->recipes()->count();
        return max(0, 10 - $used);
    }

    public function hasStripeCustomer(): bool
    {
        return !is_null($this->stripe_customer_id);
    }
}
