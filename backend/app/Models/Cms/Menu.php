<?php

namespace App\Models\Cms;

use App\Support\CmsCache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected static function booted(): void
    {
        static::saved(fn () => CmsCache::bump());
        static::deleted(fn () => CmsCache::bump());
    }

    protected $fillable = [
        'name',
        'location',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // --- Relationships ---

    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class)->whereNull('parent_id')->orderBy('sort_order');
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(MenuItem::class)->orderBy('sort_order');
    }

    // --- Helpers ---

    /**
     * Get a menu by its location with nested items.
     */
    public static function getByLocation(string $location, ?string $locale = null): ?array
    {
        $menu = self::with(['items.children'])
            ->where('location', $location)
            ->where('is_active', true)
            ->first();

        if (!$menu) {
            return null;
        }

        return [
            'name' => $menu->name,
            'location' => $menu->location,
            'items' => $menu->items
                ->filter(fn ($item) => $item->is_visible)
                ->map(fn (MenuItem $item) => $item->toApiArray($locale))
                ->values()
                ->toArray(),
        ];
    }
}
