<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Versioned cache helper for public CMS API responses.
 *
 * Every cache key includes a version number. Whenever CMS content is saved
 * or deleted, bump() increments the version so all old keys are ignored
 * instantly ("simpan = web langsung berubah"), without needing tag support
 * from the cache driver.
 */
class CmsCache
{
    public const VERSION_KEY = 'cms_cache_version';

    /** Default TTL (seconds). Kept short so scheduled banners/popups appear on time. */
    public const TTL = 300;

    public static function version(): int
    {
        return (int) Cache::rememberForever(self::VERSION_KEY, fn () => 1);
    }

    public static function key(string $key): string
    {
        return 'cms_v' . self::version() . '_' . $key;
    }

    public static function remember(string $key, \Closure $callback, ?int $ttl = null): mixed
    {
        return Cache::remember(self::key($key), $ttl ?? self::TTL, $callback);
    }

    public static function bump(): void
    {
        try {
            if (! Cache::has(self::VERSION_KEY)) {
                Cache::forever(self::VERSION_KEY, 2);

                return;
            }

            Cache::increment(self::VERSION_KEY);
        } catch (\Throwable $e) {
            // Never break a save because the cache store is unavailable.
            report($e);
        }
    }
}
