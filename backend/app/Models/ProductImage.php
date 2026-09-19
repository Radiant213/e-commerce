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

    public function getIsVideoAttribute(): bool
    {
        if ($this->media_type === 'video') {
            return true;
        }

        $raw = $this->getRawOriginal('image_path') ?? '';
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

        return url('storage/' . ltrim($value, '/'));
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
