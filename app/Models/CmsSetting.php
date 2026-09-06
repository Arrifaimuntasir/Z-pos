<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CmsSetting extends Model
{
    protected $fillable = ['page', 'key', 'value', 'type', 'label', 'sort_order'];

    /**
     * Get a CMS value by page and key, with a fallback default.
     */
    public static function get(string $page, string $key, string $default = ''): string
    {
        $cacheKey = "cms_{$page}_{$key}";
        return Cache::remember($cacheKey, 3600, function () use ($page, $key, $default) {
            $setting = static::where('page', $page)->where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Get all settings for a page as a key-value array.
     */
    public static function forPage(string $page): array
    {
        return static::where('page', $page)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('key')
            ->toArray();
    }

    /**
     * Clear cache when a setting is saved.
     */
    protected static function booted(): void
    {
        static::saved(function ($setting) {
            Cache::forget("cms_{$setting->page}_{$setting->key}");
        });
    }
}
