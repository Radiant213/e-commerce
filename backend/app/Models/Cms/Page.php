<?php

namespace App\Models\Cms;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $fillable = [
        'title',
        'title_translations',
        'slug',
        'template',
        'status',
        'show_in_nav',
        'show_in_footer',
        'sort_order',
        'created_by',
        'updated_by',
        'published_at',
    ];

    protected $casts = [
        'title_translations' => 'array',
        'show_in_nav' => 'boolean',
        'show_in_footer' => 'boolean',
        'sort_order' => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Page $page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
        });
    }

    // --- Relationships ---

    public function blocks(): HasMany
    {
        return $this->hasMany(PageBlock::class)->orderBy('sort_order');
    }

    public function seoMeta(): MorphOne
    {
        return $this->morphOne(SeoMeta::class, 'seoable');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // --- Scopes ---

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeInNav($query)
    {
        return $query->where('show_in_nav', true)->published();
    }

    public function scopeInFooter($query)
    {
        return $query->where('show_in_footer', true)->published();
    }

    // --- Helpers ---

    public function getTitle(?string $locale = null): string
    {
        if ($locale && $this->title_translations) {
            return $this->title_translations[$locale] ?? $this->title;
        }
        return $this->title;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Get page with all blocks for API response.
     */
    public function toApiArray(?string $locale = null): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getTitle($locale),
            'slug' => $this->slug,
            'template' => $this->template,
            'blocks' => $this->blocks->map(fn (PageBlock $block) => $block->toApiArray($locale))->toArray(),
            'seo' => $this->seoMeta?->toApiArray($locale),
            'published_at' => $this->published_at?->toISOString(),
        ];
    }
}
