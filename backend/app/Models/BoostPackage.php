<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BoostPackage extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'duration_days',
        'price',
        'boost_type',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get name based on locale
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $locale === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Scope for active packages
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
     * Scope by boost type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('boost_type', $type);
    }
}
