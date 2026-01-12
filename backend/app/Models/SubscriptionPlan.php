<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'description_en',
        'description_ar',
        'price_monthly',
        'listing_limit',
        'featured_quota_monthly',
        'featured_credit_days',
        'features',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price_monthly' => 'decimal:2',
        'listing_limit' => 'integer',
        'featured_quota_monthly' => 'integer',
        'featured_credit_days' => 'integer',
        'features' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Subscriptions using this plan
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get name based on locale
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Get description based on locale
     */
    public function getDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->description_ar : $this->description_en;
    }

    /**
     * Check if plan has unlimited listings
     */
    public function hasUnlimitedListings(): bool
    {
        return $this->listing_limit === null;
    }

    /**
     * Check if this is the free plan
     */
    public function isFree(): bool
    {
        return $this->slug === 'free' || $this->price_monthly == 0;
    }

    /**
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope ordered by sort_order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    /**
     * Get plans available for purchase (excludes free)
     */
    public function scopePaid($query)
    {
        return $query->where('price_monthly', '>', 0);
    }
}
