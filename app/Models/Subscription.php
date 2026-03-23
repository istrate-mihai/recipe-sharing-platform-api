<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'stripe_id',
        'stripe_customer_id',
        'stripe_price_id',
        'status',
        'ends_at',
    ];

    protected $casts = [
        'ends_at' => 'datetime',
    ];

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * A subscription is considered "active" when Stripe says so,
     * OR when it is canceled but the paid period hasn't ended yet
     * (i.e. the user canceled but still has days remaining).
     */
    public function isActive(): bool
    {
        if ($this->status === 'active') {
            return true;
        }

        if ($this->status === 'canceled' && $this->ends_at?->isFuture()) {
            return true;
        }

        return false;
    }
}
