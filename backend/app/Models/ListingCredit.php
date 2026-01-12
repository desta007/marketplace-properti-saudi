<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ListingCredit extends Model
{
    protected $fillable = [
        'user_id',
        'transaction_id',
        'credits_purchased',
        'credits_used',
        'expires_at',
    ];

    protected $casts = [
        'credits_purchased' => 'integer',
        'credits_used' => 'integer',
        'expires_at' => 'date',
    ];

    /**
     * User who owns these credits
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Transaction that created these credits
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Get remaining credits
     */
    public function getRemainingCreditsAttribute(): int
    {
        return max(0, $this->credits_purchased - $this->credits_used);
    }

    /**
     * Check if credits are expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }

    /**
     * Check if credits are usable
     */
    public function isUsable(): bool
    {
        return !$this->isExpired() && $this->remaining_credits > 0;
    }

    /**
     * Use one credit
     */
    public function useCredit(): bool
    {
        if (!$this->isUsable()) {
            return false;
        }

        $this->increment('credits_used');
        return true;
    }

    /**
     * Scope for valid (not expired) credits
     */
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
                ->orWhere('expires_at', '>=', now());
        });
    }

    /**
     * Scope for credits with remaining balance
     */
    public function scopeWithBalance($query)
    {
        return $query->whereRaw('credits_purchased > credits_used');
    }

    /**
     * Scope for usable credits (valid and with balance)
     */
    public function scopeUsable($query)
    {
        return $query->valid()->withBalance();
    }
}
