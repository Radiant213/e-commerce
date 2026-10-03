<?php

namespace App\Models\Cms;

use App\Support\CmsCache;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    /** Right side of the homepage hero (portrait slider). */
    public const PLACEMENT_HERO_SLIDER = 'hero_slider';

    /** Wide promo strip in the middle of the homepage. */
    public const PLACEMENT_PROMO_STRIP = 'promo_strip';

    public static function placementOptions(): array
    {
        return [
            self::PLACEMENT_HERO_SLIDER => 'Slider Hero (kanan atas)',
            self::PLACEMENT_PROMO_STRIP => 'Strip Promo (tengah beranda)',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => CmsCache::bump());
        static::deleted(fn () => CmsCache::bump());
    }

    protected $fillable = [
        'title',
        'title_translations',
        'subtitle',
        'subtitle_translations',
        'image',
        'image_mobile',
        'alt_text',
        'link',
        'placement',
        'cta_text',
        'cta_text_translations',
        'sort_order',
        'is_active',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'title_translations' => 'array',
        'subtitle_translations' => 'array',
        'cta_text_translations' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            });
    }

    public function scopeForPlacement($query, string $placement)
    {
        return $query->where('placement', $placement);
    }

    // --- Helpers ---

    public function getImageUrl(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return url('storage/' . ltrim($this->image, '/'));
    }

    public function getMobileImageUrl(): ?string
    {
        if (!$this->image_mobile) return $this->getImageUrl();
        if (str_starts_with($this->image_mobile, 'http')) return $this->image_mobile;
        return url('storage/' . ltrim($this->image_mobile, '/'));
    }

    public function toApiArray(?string $locale = null): array
    {
        return [
            'id' => $this->id,
            'title' => ($locale && $this->title_translations) ? ($this->title_translations[$locale] ?? $this->title) : $this->title,
            'subtitle' => ($locale && $this->subtitle_translations) ? ($this->subtitle_translations[$locale] ?? $this->subtitle) : $this->subtitle,
            'image' => $this->getImageUrl(),
            'image_mobile' => $this->getMobileImageUrl(),
            'alt_text' => $this->alt_text,
            'link' => $this->link,
            'placement' => $this->placement,
            'cta_text' => ($locale && $this->cta_text_translations) ? ($this->cta_text_translations[$locale] ?? $this->cta_text) : $this->cta_text,
        ];
    }
}
