<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'category_id', 'name', 'slug', 'description', 'short_description',
    'price', 'sale_price', 'stock', 'sku', 'weight',
    'is_active', 'is_featured', 'avg_rating', 'total_reviews', 'total_sold',
])]
class Product extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'avg_rating' => 'decimal:2',
            'weight' => 'decimal:2',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(5);
            }
            if (empty($product->sku)) {
                $product->sku = 'PRD-' . strtoupper(Str::random(6));
            }
        });
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->primaryImage?->image_path ?? $this->images->first()?->image_path;
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    // Accessors
    public function getEffectivePriceAttribute(): float
    {
        return $this->sale_price ?? $this->price;
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if ($this->sale_price && $this->price > 0) {
            return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return null;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch($query, ?string $search)
    {
        if ($search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    public function scopeByCategory($query, ?int $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopePriceRange($query, ?float $minPrice, ?float $maxPrice)
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeMinRating($query, ?float $rating)
    {
        if ($rating) {
            return $query->where('avg_rating', '>=', $rating);
        }
        return $query;
    }

    // Methods
    public function updateRatingStats(): void
    {
        $this->avg_rating = $this->reviews()->avg('rating') ?? 0;
        $this->total_reviews = $this->reviews()->count();
        $this->saveQuietly();
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
