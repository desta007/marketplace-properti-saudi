<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Property extends Model
{
    protected $fillable = [
        'user_id',
        'city_id',
        'district_id',
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'type',
        'purpose',
        'price',
        'area_sqm',
        'bedrooms',
        'bathrooms',
        'latitude',
        'longitude',
        'virtual_tour_url',
        'virtual_tour_type',
        'features',
        'rega_ad_license',
        'status',
        'view_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'features' => 'array',
        'view_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->where('is_primary', true);
    }

    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Leads/inquiries for this property
     */
    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    /**
     * All boosts for this property
     */
    public function boosts(): HasMany
    {
        return $this->hasMany(PropertyBoost::class);
    }

    /**
     * Current active boost
     */
    public function activeBoost(): HasOne
    {
        return $this->hasOne(PropertyBoost::class)
            ->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->orderByRaw("FIELD(boost_type, 'premium', 'top_pick', 'featured')")
            ->latest();
    }

    /**
     * Get title based on locale
     */
    public function getTitleAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->title_ar : $this->title_en;
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
     * Check if property has virtual tour
     */
    public function hasVirtualTour(): bool
    {
        return !empty($this->virtual_tour_url);
    }

    /**
     * Check if property is currently featured/boosted
     */
    public function isFeatured(): bool
    {
        return $this->activeBoost !== null;
    }

    /**
     * Get current boost type
     */
    public function getBoostType(): ?string
    {
        return $this->activeBoost?->boost_type;
    }

    /**
     * Get boost priority value for sorting (higher = more priority)
     */
    public function getBoostPriority(): int
    {
        $boost = $this->activeBoost;
        if (!$boost) {
            return 0;
        }

        return match ($boost->boost_type) {
            'premium' => 3,
            'top_pick' => 2,
            'featured' => 1,
            default => 0,
        };
    }

    /**
     * Get boost badge info for display
     */
    public function getBoostBadgeAttribute(): ?array
    {
        $boost = $this->activeBoost;
        if (!$boost) {
            return null;
        }

        return match ($boost->boost_type) {
            'premium' => [
                'label' => __('Premium'),
                'class' => 'bg-gradient-to-r from-yellow-400 to-amber-500 text-white',
                'icon' => 'crown',
            ],
            'top_pick' => [
                'label' => __('Top Pick'),
                'class' => 'bg-gradient-to-r from-purple-500 to-indigo-500 text-white',
                'icon' => 'star',
            ],
            'featured' => [
                'label' => __('Featured'),
                'class' => 'bg-gradient-to-r from-blue-500 to-cyan-500 text-white',
                'icon' => 'sparkles',
            ],
            default => null,
        };
    }

    /**
     * Scope for active properties
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for featured/boosted properties
     */
    public function scopeFeatured($query)
    {
        return $query->whereHas('boosts', function ($q) {
            $q->where('is_active', true)
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>=', now());
        });
    }

    /**
     * Scope to order by boost priority (featured first)
     * Uses subquery to avoid MySQL strict mode GROUP BY issues
     */
    public function scopeOrderByBoostPriority($query)
    {
        return $query->orderByRaw("
            COALESCE(
                (SELECT 
                    CASE 
                        WHEN pb.boost_type = 'premium' THEN 3
                        WHEN pb.boost_type = 'top_pick' THEN 2
                        WHEN pb.boost_type = 'featured' THEN 1
                        ELSE 0
                    END
                FROM property_boosts pb 
                WHERE pb.property_id = properties.id 
                    AND pb.is_active = 1 
                    AND pb.starts_at <= NOW() 
                    AND pb.ends_at >= NOW()
                LIMIT 1
                ), 0
            ) DESC
        ");
    }

    /**
     * Scope for filtering by purpose
     */
    public function scopePurpose($query, $purpose)
    {
        if ($purpose) {
            return $query->where('purpose', $purpose);
        }
        return $query;
    }

    /**
     * Scope for filtering by type
     */
    public function scopeType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    /**
     * Scope for filtering by city
     */
    public function scopeInCity($query, $cityId)
    {
        if ($cityId) {
            return $query->where('city_id', $cityId);
        }
        return $query;
    }

    /**
     * Scope for map bounding box search
     */
    public function scopeInBoundingBox($query, $minLat, $maxLat, $minLng, $maxLng)
    {
        return $query->whereBetween('latitude', [$minLat, $maxLat])
            ->whereBetween('longitude', [$minLng, $maxLng]);
    }

    /**
     * Scope for price range filter
     */
    public function scopePriceRange($query, $minPrice = null, $maxPrice = null)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    /**
     * Scope for properties with virtual tour
     */
    public function scopeWithVirtualTour($query)
    {
        return $query->whereNotNull('virtual_tour_url');
    }
}


