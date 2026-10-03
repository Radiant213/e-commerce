<?php

namespace App\Models\Cms;

use App\Support\CmsCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBlock extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => CmsCache::bump());
        static::deleted(fn () => CmsCache::bump());
    }

    protected $fillable = [
        'page_id',
        'type',
        'content',
        'content_translations',
        'settings',
        'sort_order',
        'is_visible',
    ];

    protected $casts = [
        'content' => 'array',
        'content_translations' => 'array',
        'settings' => 'array',
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
    ];

    /**
     * Available block types with their content schemas.
     */
    public const TYPES = [
        'heading' => ['text', 'level'], // h1-h6
        'text' => ['body'], // Rich text / markdown
        'image' => ['src', 'alt', 'caption', 'width'],
        'gallery' => ['images'], // Array of {src, alt, caption}
        'accordion' => ['items'], // Array of {title, content}
        'tabs' => ['items'], // Array of {label, content}
        'embed' => ['url', 'type'], // youtube, vimeo, etc.
        'cta' => ['title', 'description', 'button_text', 'button_link', 'style'],
        'divider' => ['style'], // solid, dashed, dotted, space
        'code' => ['language', 'code'],
        'list' => ['items', 'ordered'], // Array of strings
        'quote' => ['text', 'author'],
        'columns' => ['columns'], // Array of {blocks: [...]}
    ];

    // --- Relationships ---

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    // --- Helpers ---

    /**
     * Get localized content for API.
     */
    public function getContent(?string $locale = null): array
    {
        if ($locale && $this->content_translations) {
            $translations = $this->content_translations[$locale] ?? [];
            return array_merge($this->content ?? [], $translations);
        }
        return $this->content ?? [];
    }

    public function toApiArray(?string $locale = null): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'content' => $this->getContent($locale),
            'settings' => $this->settings,
            'sort_order' => $this->sort_order,
        ];
    }
}
