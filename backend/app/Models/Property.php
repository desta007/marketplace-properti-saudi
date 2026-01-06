<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Scope for active properties
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
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
}
