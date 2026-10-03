<?php

namespace App\Models\Cms;

use App\Support\CmsCache;
use Illuminate\Database\Eloquent\Model;

class HomepageSection extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => CmsCache::bump());
        static::deleted(fn () => CmsCache::bump());
    }

    protected $fillable = [
        'type',
        'title',
        'config',
        'config_translations',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'config' => 'array',
        'config_translations' => 'array',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Available section types.
     */
    public const TYPES = [
        'hero' => 'Hero (bagian paling atas)',
        'categories' => 'Kategori Pilihan',
        'product_showcase' => 'Etalase Produk',
        'promo_strip' => 'Strip Promo (banner tengah)',
        'brand_story' => 'Cerita Brand',
        'newsletter' => 'Langganan Newsletter',
        'custom_banner' => 'Custom Banner',
        'custom_html' => 'Custom HTML/Content',
    ];

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // --- Helpers ---

    /**
     * Get config with translation overlay.
     */
    public function getConfig(?string $locale = null): array
    {
        $config = $this->config ?? [];

        if ($locale && $this->config_translations) {
            $translations = $this->config_translations[$locale] ?? [];
            $config = array_merge($config, $translations);
        }

        // Uploaded images are stored as relative paths on the public disk.
        if (! empty($config['image']) && is_string($config['image']) && ! str_starts_with($config['image'], 'http')) {
            $config['image'] = url('storage/' . ltrim($config['image'], '/'));
        }

        return $config;
    }

    public function toApiArray(?string $locale = null): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'title' => $this->title,
            'config' => $this->getConfig($locale),
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
    }
}
