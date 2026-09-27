<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CmsSetting extends Model
{
    protected $fillable = [
        'key',
        'label',
        'value',
        'type',
        'group',
        'tab',
        'sort_order',
        'options',
        'is_translatable',
        'translations',
    ];

    protected $casts = [
        'options' => 'array',
        'translations' => 'array',
        'is_translatable' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get a CMS setting value by key.
     * Returns translation if available for the given locale.
     */
    public static function getValue(string $key, $default = null, ?string $locale = null)
    {
        $setting = Cache::remember("cms_setting_{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        // Return translation if requested and available
        if ($locale && $setting->is_translatable && $setting->translations) {
            return $setting->translations[$locale] ?? $setting->value ?? $default;
        }

        // Cast based on type
        return match ($setting->type) {
            'number' => (float) $setting->value,
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($setting->value, true),
            default => $setting->value ?? $default,
        };
    }

    /**
     * Set a CMS setting value by key.
     */
    public static function setValue(string $key, $value, ?array $translations = null): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => is_array($value) ? json_encode($value) : $value]
        );

        if ($translations !== null) {
            $setting->update(['translations' => $translations]);
        }

        // Clear cache for this key
        Cache::forget("cms_setting_{$key}");
        Cache::forget('cms_settings_all');

        return $setting;
    }

    /**
     * Get all settings for a group, optionally filtered by tab.
     */
    public static function getGroup(string $group, ?string $tab = null): array
    {
        $cacheKey = "cms_settings_group_{$group}" . ($tab ? "_{$tab}" : '');

        return Cache::remember($cacheKey, 3600, function () use ($group, $tab) {
            $query = self::where('group', $group)->orderBy('sort_order');

            if ($tab) {
                $query->where('tab', $tab);
            }

            return $query->get()->mapWithKeys(function ($setting) {
                return [$setting->key => $setting->value];
            })->toArray();
        });
    }

    /**
     * Get all settings as a flat key-value array (for API response).
     */
    public static function getAllForApi(?string $locale = null): array
    {
        return Cache::remember("cms_settings_api_{$locale}", 3600, function () use ($locale) {
            return self::all()->mapWithKeys(function ($setting) use ($locale) {
                $value = $setting->value;

                if ($locale && $setting->is_translatable && $setting->translations) {
                    $value = $setting->translations[$locale] ?? $value;
                }

                // Cast value based on type
                $value = match ($setting->type) {
                    'number' => (float) $value,
                    'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
                    'json' => json_decode($value, true),
                    'image' => $value ? url('storage/' . ltrim($value, '/')) : null,
                    default => $value,
                };

                return [$setting->key => $value];
            })->toArray();
        });
    }

    /**
     * Clear all CMS settings cache.
     */
    public static function clearCache(): void
    {
        $settings = self::all();
        foreach ($settings as $setting) {
            Cache::forget("cms_setting_{$setting->key}");
        }
        Cache::forget('cms_settings_all');
        Cache::forget('cms_settings_api_en');
        Cache::forget('cms_settings_api_id');
        Cache::forget('cms_settings_api_');

        // Clear group caches
        $groups = self::distinct('group')->pluck('group');
        foreach ($groups as $group) {
            Cache::forget("cms_settings_group_{$group}");
        }
    }
}
