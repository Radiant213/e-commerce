<?php

namespace App\Models\Cms;

use App\Support\CmsCache;
use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => CmsCache::bump());
        static::deleted(fn () => CmsCache::bump());
    }

    protected $fillable = [
        'name',
        'title',
        'title_translations',
        'description',
        'description_translations',
        'image',
        'type',
        'cta_text',
        'cta_text_translations',
        'cta_link',
        'delay_seconds',
        'show_once_per_session',
        'is_active',
        'starts_at',
        'ends_at',
        'show_on',
    ];

    protected $casts = [
        'title_translations' => 'array',
        'description_translations' => 'array',
        'cta_text_translations' => 'array',
        'delay_seconds' => 'integer',
        'show_once_per_session' => 'boolean',
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

    // --- Helpers ---

    public function getImageUrl(): ?string
    {
        if (!$this->image) return null;
        if (str_starts_with($this->image, 'http')) return $this->image;
        return url('storage/' . ltrim($this->image, '/'));
    }

    public function toApiArray(?string $locale = null): array
    {
        return [
            'id' => $this->id,
            'title' => ($locale && $this->title_translations) ? ($this->title_translations[$locale] ?? $this->title) : $this->title,
            'description' => ($locale && $this->description_translations) ? ($this->description_translations[$locale] ?? $this->description) : $this->description,
            'image' => $this->getImageUrl(),
            'type' => $this->type,
            'cta_text' => ($locale && $this->cta_text_translations) ? ($this->cta_text_translations[$locale] ?? $this->cta_text) : $this->cta_text,
            'cta_link' => $this->cta_link,
            'delay_seconds' => $this->delay_seconds,
            'show_once_per_session' => $this->show_once_per_session,
            'show_on' => $this->show_on ?: 'all',
        ];
    }
}
