<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PropertyBoost extends Model
{
    protected $fillable = [
        'property_id',
        'transaction_id',
        'boost_type',
        'starts_at',
        'ends_at',
        'is_active',
        'from_subscription_credit',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
        'from_subscription_credit' => 'boolean',
    ];

    const TYPE_FEATURED = 'featured';
    const TYPE_TOP_PICK = 'top_pick';
    const TYPE_PREMIUM = 'premium';

    /**
     * Property being boosted
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Transaction that created this boost
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    /**
     * Check if boost is currently active
     */
    public function isCurrentlyActive(): bool
    {
        return $this->is_active
            && $this->starts_at <= now()
            && $this->ends_at >= now();
    }

    /**
     * Check if boost is expired
     */
    public function isExpired(): bool
    {
        return $this->ends_at < now();
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute(): int
    {
        if ($this->isExpired()) {
            return 0;
        }

        return max(0, Carbon::now()->diffInDays($this->ends_at, false));
    }

    /**
     * Get boost type label
     */
    public function getBoostTypeLabelAttribute(): string
    {
        return match ($this->boost_type) {
            self::TYPE_FEATURED => __('Featured'),
            self::TYPE_TOP_PICK => __('Top Pick'),
            self::TYPE_PREMIUM => __('Premium'),
            default => $this->boost_type,
        };
    }

    /**
     * Scope for currently active boosts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    /**
     * Scope for expired boosts
     */
    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<', now());
    }

    /**
     * Scope by boost type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('boost_type', $type);
    }

    /**
     * Get boost priority value (for sorting)
     */
    public function getBoostPriorityAttribute(): int
    {
        return match ($this->boost_type) {
            self::TYPE_PREMIUM => 3,
            self::TYPE_TOP_PICK => 2,
            self::TYPE_FEATURED => 1,
            default => 0,
        };
    }
}
