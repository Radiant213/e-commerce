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
     * Check if a URL is from Google Images or Google redirect.
     */
    public static function isGoogleImage(?string $url): bool
    {
        if (blank($url)) {
            return false;
        }

        return (bool) preg_match('#(google\.[a-z\.]+\/(?:imgres|url)|images\.app\.goo\.gl|gstatic\.com)#i', $url);
    }

    /**
     * Check if string is a base64 Data URI.
     */
    public static function isDataUri(?string $str): bool
    {
        if (blank($str)) {
            return false;
        }

        return (bool) preg_match('#^data:image\/([a-zA-Z0-9\+\-]+);base64,#i', $str);
    }

    /**
     * Store a base64 Data URI as a local image file.
     */
    public static function storeDataUri(string $dataUri, string $folder = 'products'): ?string
    {
        if (!preg_match('#^data:image\/([a-zA-Z0-9\+\-]+);base64,(.+)$#is', $dataUri, $matches)) {
            return null;
        }

        $ext = strtolower($matches[1]);
        if ($ext === 'jpeg') $ext = 'jpg';
        if ($ext === 'svg+xml') $ext = 'svg';

        $bytes = base64_decode($matches[2]);
        if (!$bytes) {
            return null;
        }

        $filename = md5(microtime() . Str::random(10)) . '.' . $ext;
        $storagePath = trim($folder, '/') . '/' . $filename;

        Storage::disk('public')->put($storagePath, $bytes);

        return $storagePath;
    }

    /**
     * Resolve actual direct image URL from a Google Images link.
     */
    public static function resolveGoogleImage(string $url): ?string
    {
        $url = trim($url);

        // 1. Direct gstatic thumbnail
        if (str_contains($url, 'gstatic.com/images')) {
            return $url;
        }

        // 2. Short link (images.app.goo.gl): follow redirects to get final URL
        if (str_contains($url, 'images.app.goo.gl') || str_contains($url, 'goo.gl')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_exec($ch);
            $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            curl_close($ch);

            if ($finalUrl && $finalUrl !== $url) {
                $url = $finalUrl;
            }
        }

        // 3. Extract imgurl or q parameter from Google imgres / url query
        $queryString = parse_url($url, PHP_URL_QUERY);
        if ($queryString) {
            parse_str($queryString, $params);
            if (!empty($params['imgurl'])) {
                return urldecode($params['imgurl']);
            }
            if (!empty($params['q']) && filter_var($params['q'], FILTER_VALIDATE_URL)) {
                return urldecode($params['q']);
            }
        }

        return $url;
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
     * Scrape main image (og:image / twitter:image) from arbitrary HTML webpage.
     */
    public static function extractImageFromHtml(string $html): ?string
    {
        if (preg_match('/<meta\s+property=["\']og:image["\']\s+content=["\']([^"\']+)["\']/i', $html, $m)) {
            return html_entity_decode($m[1]);
        }
        if (preg_match('/<meta\s+content=["\']([^"\']+)["\']\s+property=["\']og:image["\']/i', $html, $m)) {
            return html_entity_decode($m[1]);
        }
        if (preg_match('/<meta\s+name=["\']twitter:image["\']\s+content=["\']([^"\']+)["\']/i', $html, $m)) {
            return html_entity_decode($m[1]);
        }
        if (preg_match('/<link\s+rel=["\']image_src["\']\s+href=["\']([^"\']+)["\']/i', $html, $m)) {
            return html_entity_decode($m[1]);
        }

        return null;
    }

    /**
     * Download an online image with proper headers and store it on the public disk.
     * Automatically bypasses hotlink blocks (Referer spoofing) and follows redirects.
     *
     * @param string $imageUrl The image URL
     * @param string $folder Storage folder under public disk
     * @return string|null Relative storage path (e.g. products/hash.jpg) or null on failure
     */
    public static function downloadAndStoreImage(string $imageUrl, string $folder = 'products'): ?string
    {
        try {
            $imageUrl = trim($imageUrl);

            // Determine referer
            $referer = 'https://www.google.com/';
            if (self::isPinterest($imageUrl)) {
                $referer = 'https://www.pinterest.com/';
            } else {
                $parsedHost = parse_url($imageUrl, PHP_URL_HOST);
                if ($parsedHost) {
                    $referer = 'https://' . $parsedHost . '/';
                }
            }

            $ch = curl_init($imageUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36');
            curl_setopt($ch, CURLOPT_REFERER, $referer);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
                'Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7',
                'Sec-Fetch-Dest: image',
                'Sec-Fetch-Mode: no-cors',
                'Sec-Fetch-Site: cross-site',
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 18);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $bytes = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = strtolower(curl_getinfo($ch, CURLINFO_CONTENT_TYPE) ?: '');
            curl_close($ch);

            if ($code !== 200 || empty($bytes)) {
                return null;
            }

            // If response is HTML (e.g. user pasted a webpage link or Google redirect), try scraping image from HTML
            if (str_contains($contentType, 'text/html') || str_starts_with(ltrim($bytes), '<!DOCTYPE') || str_starts_with(ltrim($bytes), '<html')) {
                $scraped = self::extractImageFromHtml($bytes);
                if ($scraped && filter_var($scraped, FILTER_VALIDATE_URL) && $scraped !== $imageUrl) {
                    return self::downloadAndStoreImage($scraped, $folder);
                }
                return null;
            }

            // Determine proper extension
            $ext = 'jpg';
            if (str_contains($contentType, 'png')) {
                $ext = 'png';
            } elseif (str_contains($contentType, 'webp')) {
                $ext = 'webp';
            } elseif (str_contains($contentType, 'gif')) {
                $ext = 'gif';
            } elseif (str_contains($contentType, 'svg')) {
                $ext = 'svg';
            } elseif (str_contains($contentType, 'avif')) {
                $ext = 'avif';
            } else {
                $pathExt = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
                if (in_array($pathExt, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'avif'])) {
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
     * Process any image input (Google link, Pinterest, direct image, web link, or data URI).
     * Attempts to resolve, download, and store locally in public disk storage.
     * Returns local relative path (e.g. products/xyz.jpg) or valid URL.
     */
    public static function processImageUrl(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $url = trim($url);

        // 1. Data URI base64
        if (self::isDataUri($url)) {
            return self::storeDataUri($url, 'products') ?: null;
        }

        // 2. Already a relative storage path or local file
        if (!str_starts_with($url, 'http://') && !str_starts_with($url, 'https://')) {
            return $url;
        }

        // 3. Google Images URL
        if (self::isGoogleImage($url)) {
            $resolved = self::resolveGoogleImage($url) ?: $url;
            $stored = self::downloadAndStoreImage($resolved, 'products');
            return $stored ?: $resolved;
        }

        // 4. Pinterest link
        if (self::isPinterest($url)) {
            $resolved = self::resolvePinterestImage($url) ?: $url;
            $stored = self::downloadAndStoreImage($resolved, 'products');
            return $stored ?: $resolved;
        }

        // 5. Generic online image: download and store locally to prevent hotlinking 403
        $stored = self::downloadAndStoreImage($url, 'products');
        return $stored ?: $url;
    }
}
