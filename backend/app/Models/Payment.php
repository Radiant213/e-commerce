<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'order_id', 'midtrans_transaction_id', 'snap_token',
    'payment_type', 'status', 'gross_amount', 'midtrans_response',
])]
class Payment extends Model
{
    protected function casts(): array
    {
        return [
            'gross_amount' => 'decimal:2',
            'midtrans_response' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isSettled(): bool
    {
        return $this->status === 'settlement' || $this->status === 'capture';
    }
}
