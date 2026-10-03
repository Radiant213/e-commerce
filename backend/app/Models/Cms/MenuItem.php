<?php

namespace App\Models\Cms;

use App\Support\CmsCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MenuItem extends Model
{
    protected static function booted(): void
    {
        static::saving(function (self $item) {
            // The CMS form edits the Indonesian label directly. Drop the stale
            // 'id' translation so it no longer overrides the edited label.
            if ($item->exists && $item->isDirty('label') && is_array($item->label_translations)) {
                $translations = $item->label_translations;
                unset($translations['id']);
                $item->label_translations = $translations ?: null;
            }
        });

        static::saved(fn () => CmsCache::bump());
        static::deleted(fn () => CmsCache::bump());
    }

    protected $fillable = [
        'menu_id',
        'parent_id',
        'label',
        'label_translations',
        'url',
        'type',
        'linkable_id',
        'linkable_type',
        'target',
        'icon',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'label_translations' => 'array',
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
    ];

    // --- Relationships ---

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }

    public function linkable(): MorphTo
    {
        return $this->morphTo();
    }

    // --- Helpers ---

    public function getLabel(?string $locale = null): string
    {
        if ($locale && $this->label_translations) {
            return $this->label_translations[$locale] ?? $this->label;
        }
        return $this->label;
    }

    /**
     * Resolve the actual URL based on type.
     */
    public function getResolvedUrl(): ?string
    {
        if ($this->url) {
            return $this->url;
        }

        if ($this->linkable) {
            return match ($this->type) {
                'page' => "/pages/{$this->linkable->slug}",
                'category' => "/products?category_id={$this->linkable->id}",
                'product' => "/products/{$this->linkable->slug}",
                default => null,
            };
        }

        return null;
    }

    public function toApiArray(?string $locale = null): array
    {
        return [
            'id' => $this->id,
            'label' => $this->getLabel($locale),
            'url' => $this->getResolvedUrl(),
            'target' => $this->target,
            'icon' => $this->icon,
            'children' => $this->children
                ->filter(fn ($child) => $child->is_visible)
                ->map(fn (self $child) => $child->toApiArray($locale))
                ->values()
                ->toArray(),
        ];
    }
}
