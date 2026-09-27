<?php

namespace App\Models\Cms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    protected $fillable = [
        'seoable_id',
        'seoable_type',
        'meta_title',
        'meta_title_translations',
        'meta_description',
        'meta_description_translations',
        'og_image',
        'canonical_url',
        'extra_meta',
    ];

    protected $casts = [
        'meta_title_translations' => 'array',
        'meta_description_translations' => 'array',
        'extra_meta' => 'array',
    ];

    // --- Relationships ---

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    // --- Helpers ---

    public function getOgImageUrl(): ?string
    {
        if (!$this->og_image) return null;
        if (str_starts_with($this->og_image, 'http')) return $this->og_image;
        return url('storage/' . ltrim($this->og_image, '/'));
    }

    public function toApiArray(?string $locale = null): array
    {
        return [
            'meta_title' => ($locale && $this->meta_title_translations) ? ($this->meta_title_translations[$locale] ?? $this->meta_title) : $this->meta_title,
            'meta_description' => ($locale && $this->meta_description_translations) ? ($this->meta_description_translations[$locale] ?? $this->meta_description) : $this->meta_description,
            'og_image' => $this->getOgImageUrl(),
            'canonical_url' => $this->canonical_url,
            'extra_meta' => $this->extra_meta,
        ];
    }
}
