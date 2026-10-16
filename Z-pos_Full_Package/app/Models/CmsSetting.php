<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CmsSetting extends Model
{
    protected $fillable = ['page', 'key', 'value', 'type', 'label', 'sort_order'];

    /**
     * Default pricing values per plan.
     */
    private static array $defaultPrices = [
        'starter'      => ['monthly' => 15000,  'yearly' => 150000],
        'professional' => ['monthly' => 45000,  'yearly' => 450000],
        'enterprise'   => ['monthly' => 110000, 'yearly' => 1100000],
    ];

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
     * Get monthly price for a given plan (integer, e.g. 15000).
     */
    public static function monthlyPriceValue(string $plan): int
    {
        $stored = static::get('pricing', "{$plan}_monthly_price", '');
        if ($stored !== '' && is_numeric($stored)) {
            return (int) $stored;
        }
        return static::$defaultPrices[$plan]['monthly'] ?? 0;
    }

    /**
     * Get yearly price for a given plan (integer, e.g. 150000).
     */
    public static function yearlyPriceValue(string $plan): int
    {
        $stored = static::get('pricing', "{$plan}_yearly_price", '');
        if ($stored !== '' && is_numeric($stored)) {
            return (int) $stored;
        }
        return static::$defaultPrices[$plan]['yearly'] ?? 0;
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
