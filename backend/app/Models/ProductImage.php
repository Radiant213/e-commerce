<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['product_id', 'image_path', 'is_primary', 'sort_order', 'media_type'])]
class ProductImage extends Model
{
    protected $appends = ['is_video'];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (ProductImage $image) {
            if (blank($image->getRawOriginal('image_path') ?? $image->image_path)) {
                return false;
            }
        });
    }

    public function setImagePathAttribute($value): void
    {
        if (blank($value)) {
            $this->attributes['image_path'] = null;
            return;
        }

        $clean = trim((string) $value);

        // If it is a video (marked or YouTube link)
        if (($this->media_type ?? 'image') === 'video' || \App\Services\MediaUrlService::isYouTube($clean)) {
            $this->attributes['media_type'] = 'video';
            $this->attributes['image_path'] = \App\Services\MediaUrlService::processVideoUrl($clean);
            return;
        }

        // Online image URL, Google link, Pinterest, or Data URI: resolve and cache locally
        if (str_starts_with($clean, 'http://') || str_starts_with($clean, 'https://') || str_starts_with($clean, 'data:image/')) {
            $this->attributes['image_path'] = \App\Services\MediaUrlService::processImageUrl($clean);
            return;
        }

        $this->attributes['image_path'] = $clean;
    }

    public function getIsVideoAttribute(): bool
    {
        if ($this->media_type === 'video') {
            return true;
        }

        $raw = $this->getRawOriginal('image_path') ?? $this->image_path ?? '';
        if (\App\Services\MediaUrlService::isYouTube($raw)) {
            return true;
        }

        $ext = strtolower(pathinfo(parse_url($raw, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));

        return in_array($ext, ['mp4', 'mov', 'webm', 'ogg']);
    }

    public function getImagePathAttribute($value): ?string
    {
        if (blank($value)) {
            return null;
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        if (request() && request()->header('host')) {
            $scheme = (request()->secure() || request()->header('x-forwarded-proto') === 'https') ? 'https' : request()->getScheme();
            return $scheme . '://' . request()->header('host') . '/storage/' . ltrim($value, '/');
        }

        return url('storage/' . ltrim($value, '/'));
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
