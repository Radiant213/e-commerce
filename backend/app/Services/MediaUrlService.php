<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaUrlService
{
    /**
     * Check if a given URL is a YouTube link.
     */
    public static function isYouTube(?string $url): bool
    {
        if (blank($url)) {
            return false;
        }

        return (bool) preg_match('#(youtube\.com|youtu\.be)#i', $url);
    }

    /**
     * Extract the 11-character YouTube video ID.
     */
    public static function getYouTubeVideoId(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $pattern = '#(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?|shorts)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})#i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get a standardized YouTube embed URL.
     */
    public static function getYouTubeEmbedUrl(?string $url): ?string
    {
        $id = self::getYouTubeVideoId($url);
        return $id ? "https://www.youtube-nocookie.com/embed/{$id}" : null;
    }

    /**
     * Get high-quality YouTube video thumbnail.
     */
    public static function getYouTubeThumbnailUrl(?string $url): ?string
    {
        $id = self::getYouTubeVideoId($url);
        return $id ? "https://img.youtube.com/vi/{$id}/hqdefault.jpg" : null;
    }

    /**
     * Normalize YouTube link to standard watch link.
     */
    public static function processVideoUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $url = trim($url);
        $ytId = self::getYouTubeVideoId($url);
        if ($ytId) {
            return "https://www.youtube.com/watch?v={$ytId}";
        }

        return $url;
    }

    /**
     * Check if a URL is from Pinterest.
     */
    public static function isPinterest(?string $url): bool
    {
        if (blank($url)) {
            return false;
        }

        return (bool) preg_match('#(pin\.it|pinterest\.[a-z\.]+|pinimg\.com)#i', $url);
    }

    /**
     * Resolve the direct image URL from a Pinterest link (pin.it or pinterest.com/pin/...).
     */
    public static function resolvePinterestImage(string $url): ?string
    {
        $url = trim($url);

        // If it's already a direct pinimg link, return it
        if (str_contains($url, 'i.pinimg.com')) {
            return $url;
        }

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 12);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $html = curl_exec($ch);
            curl_close($ch);

            if (!$html) {
                return null;
            }

            // 1. Search for highest resolution pinimg link (originals, 1200x, 736x)
            if (preg_match('#https://i\.pinimg\.com/originals/[^"\'\s<>]+\.(?:jpg|jpeg|png|webp)#i', $html, $m)) {
                return $m[0];
            }
            if (preg_match('#https://i\.pinimg\.com/1200x/[^"\'\s<>]+\.(?:jpg|jpeg|png|webp)#i', $html, $m)) {
                return $m[0];
            }
            if (preg_match('#https://i\.pinimg\.com/736x/[^"\'\s<>]+\.(?:jpg|jpeg|png|webp)#i', $html, $m)) {
                return $m[0];
            }
            if (preg_match('#https://i\.pinimg\.com/564x/[^"\'\s<>]+\.(?:jpg|jpeg|png|webp)#i', $html, $m)) {
                return $m[0];
            }

            // 2. Search for meta og:image or twitter:image
            if (preg_match('/<meta\s+property="og:image"\s+content="([^"]+)"/i', $html, $m)) {
                return $m[1];
            }
            if (preg_match('/<meta\s+name="twitter:image"\s+content="([^"]+)"/i', $html, $m)) {
                return $m[1];
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to resolve Pinterest image: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Download an online image (including Pinterest with proper headers)
     * and save it to public disk storage (e.g. storage/app/public/products/...).
     *
     * @param string $imageUrl The direct image URL
     * @param string $folder Storage folder under public disk
     * @return string|null Relative storage path (e.g. products/hash.jpg) or null on failure
     */
    public static function downloadAndStoreImage(string $imageUrl, string $folder = 'products'): ?string
    {
        try {
            $ch = curl_init($imageUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36');
            if (self::isPinterest($imageUrl)) {
                curl_setopt($ch, CURLOPT_REFERER, 'https://www.pinterest.com/');
            }
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $bytes = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '';
            curl_close($ch);

            if ($code !== 200 || empty($bytes) || strlen($bytes) < 500) {
                return null;
            }

            // Detect extension from content type or path
            $ext = 'jpg';
            if (str_contains($contentType, 'png')) {
                $ext = 'png';
            } elseif (str_contains($contentType, 'webp')) {
                $ext = 'webp';
            } elseif (str_contains($contentType, 'gif')) {
                $ext = 'gif';
            } else {
                $pathExt = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                if (in_array($pathExt, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
                    $ext = $pathExt;
                }
            }

            $filename = md5($imageUrl . microtime()) . '.' . $ext;
            $storagePath = trim($folder, '/') . '/' . $filename;

            Storage::disk('public')->put($storagePath, $bytes);

            return $storagePath;
        } catch (\Throwable $e) {
            Log::warning('MediaUrlService failed to download image: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Process an image URL (Pinterest or any online link).
     * Attempts to resolve, download, and store locally.
     * Falls back to resolved URL if download fails.
     */
    public static function processImageUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $url = trim($url);

        // If not a URL (already a storage path or local file), keep as is
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return $url;
        }

        // Pinterest handling
        if (self::isPinterest($url)) {
            $resolved = self::resolvePinterestImage($url) ?: $url;
            $storedPath = self::downloadAndStoreImage($resolved, 'products');
            return $storedPath ?: $resolved;
        }

        // General external image: attempt to download and cache locally
        $stored = self::downloadAndStoreImage($url, 'products');
        return $stored ?: $url;
    }
}
