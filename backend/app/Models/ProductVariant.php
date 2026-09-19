<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'product_id',
    'name',
    'sku',
    'price',
    'sale_price',
    'stock',
    'image_path',
    'is_active',
    'sort_order',
])]
class ProductVariant extends Model
{
    protected $appends = ['image_url', 'effective_price'];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'stock' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (blank($this->image_path)) {
            return null;
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        return url('storage/' . ltrim($this->image_path, '/'));
    }

    public function getEffectivePriceAttribute(): ?float
    {
        if ($this->sale_price) {
            return (float) $this->sale_price;
        }

        if ($this->price) {
            return (float) $this->price;
        }

        return $this->product ? (float) $this->product->effective_price : null;
    }
}

