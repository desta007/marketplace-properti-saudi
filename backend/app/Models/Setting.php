<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
        'label',
    ];

    /**
     * Cache key prefix
     */
    protected static string $cachePrefix = 'setting_';

    /**
     * Cache duration in seconds (1 hour)
     */
    protected static int $cacheDuration = 3600;

    /**
     * Get a setting value by key
     */
    public static function get(string $key, $default = null): mixed
    {
        return Cache::remember(self::$cachePrefix . $key, self::$cacheDuration, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set(string $key, $value): bool
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Clear cache for this key
        Cache::forget(self::$cachePrefix . $key);

        return $setting->wasRecentlyCreated || $setting->wasChanged();
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('group', $group)->get();
    }

    /**
     * Clear all settings cache
     */
    public static function clearCache(): void
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget(self::$cachePrefix . $setting->key);
        }
    }
}
