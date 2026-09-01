<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['user_id', 'product_id', 'order_id', 'rating', 'comment'])]
class Review extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(function (Review $review) {
            $review->product->updateRatingStats();
        });

        static::updated(function (Review $review) {
            $review->product->updateRatingStats();
        });

        static::deleted(function (Review $review) {
            $review->product->updateRatingStats();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isVerifiedPurchase(): bool
    {
        return $this->order_id !== null;
    }
}
