<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create a Snap payment token for an order.
     */
    public function createSnapToken(Order $order): string
    {
        // If payment already exists with a valid Snap Token, return it directly
        if ($order->payment && !empty($order->payment->snap_token) && !str_starts_with($order->payment->snap_token, 'dummy')) {
            return $order->payment->snap_token;
        }

        $items = $order->items->map(function ($item) {
            return [
                'id' => (string) $item->product_id,
                'price' => (int) $item->product_price,
                'quantity' => (int) $item->quantity,
                'name' => substr($item->product_name, 0, 50),
            ];
        })->toArray();

        // Add shipping cost as item if > 0
        if ($order->shipping_cost > 0) {
            $items[] = [
                'id' => 'SHIPPING',
                'price' => (int) $order->shipping_cost,
                'quantity' => 1,
                'name' => 'Biaya Pengiriman',
            ];
        }

        $buildParams = function ($orderNum) use ($order, $items) {
            return [
                'transaction_details' => [
                    'order_id' => $orderNum,
                    'gross_amount' => (int) $order->total,
                ],
                'item_details' => $items,
                'customer_details' => [
                    'first_name' => $order->shipping_name,
                    'email' => $order->user->email,
                    'phone' => $order->shipping_phone,
                    'shipping_address' => [
                        'first_name' => $order->shipping_name,
                        'phone' => $order->shipping_phone,
                        'address' => $order->shipping_address,
                        'city' => $order->shipping_city ?? '',
                        'postal_code' => $order->shipping_postal_code ?? '',
                        'country_code' => 'IDN',
                    ],
                ],
            ];
        };

        try {
            $snapToken = Snap::getSnapToken($buildParams($order->order_number));
        } catch (\Throwable $e) {
            // If Midtrans reports order_id is already used, generate a fresh unique order_number and retry
            if (str_contains(strtolower($e->getMessage()), 'sudah digunakan') || str_contains(strtolower($e->getMessage()), 'already been used') || str_contains(strtolower($e->getMessage()), 'order_id')) {
                $order->order_number = 'ORD-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));
                $order->save();

                $snapToken = Snap::getSnapToken($buildParams($order->order_number));
            } else {
                throw $e;
            }
        }

        // Create or update payment record
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'snap_token' => $snapToken,
                'gross_amount' => $order->total,
                'status' => 'pending',
            ]
        );

        return $snapToken;
    }

    /**
     * Handle MidTrans webhook notification.
     */
    public function handleNotification(): ?Payment
    {
        if (request()->isMethod('get') || empty(request()->all())) {
            return null;
        }

        try {
            $notification = new Notification();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info('Midtrans Ping / Raw notification received: ' . $e->getMessage());
            return null;
        }

        $transactionStatus = $notification->transaction_status ?? null;
        $paymentType = $notification->payment_type ?? null;
        $orderId = $notification->order_id ?? null;
        $fraudStatus = $notification->fraud_status ?? null;

        if (!$orderId) {
            return null;
        }

        // Find order by order_number
        $order = Order::where('order_number', $orderId)->first();
        if (!$order) {
            \Illuminate\Support\Facades\Log::info("Midtrans Test / Unknown Order received: {$orderId}");
            return null;
        }

        $payment = $order->payment;

        if (!$payment) {
            $payment = Payment::create([
                'order_id' => $order->id,
                'gross_amount' => $order->total,
            ]);
        }

        $payment->midtrans_transaction_id = $notification->transaction_id ?? null;
        $payment->payment_type = $paymentType ?? 'other';
        $payment->midtrans_response = json_decode(json_encode($notification), true);

        // Map transaction status
        if ($transactionStatus === 'capture') {
            $payment->status = ($fraudStatus === 'accept') ? 'settlement' : 'deny';
        } elseif ($transactionStatus === 'settlement') {
            $payment->status = 'settlement';
        } elseif ($transactionStatus === 'pending') {
            $payment->status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            $payment->status = $transactionStatus;
        }

        $payment->save();

        // Update order status based on payment
        if ($payment->isSettled()) {
            $order->update(['status' => 'paid']);
            try {
                \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\PaymentSuccessMail($order));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed sending payment success email: ' . $e->getMessage());
            }
        } elseif (in_array($payment->status, ['expire', 'cancel', 'deny'])) {
            $order->update(['status' => 'cancelled']);
            // Restore product stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        return $payment;
    }

    /**
     * Actively query Midtrans API and sync order status in real-time.
     */
    public function syncOrderStatus(Order $order): Order
    {
        if ($order->status !== 'pending') {
            return $order;
        }

        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$clientKey = config('midtrans.client_key');
            Config::$isProduction = config('midtrans.is_production');

            $statusResponse = \Midtrans\Transaction::status($order->order_number);
            $statusArray = is_array($statusResponse) ? $statusResponse : (array) $statusResponse;
            $transactionStatus = $statusArray['transaction_status'] ?? null;
            $fraudStatus = $statusArray['fraud_status'] ?? null;
            $paymentType = $statusArray['payment_type'] ?? null;
            $transactionId = $statusArray['transaction_id'] ?? null;

            if ($transactionStatus) {
                $payment = $order->payment;
                if (!$payment) {
                    $payment = Payment::create([
                        'order_id' => $order->id,
                        'gross_amount' => $order->total,
                    ]);
                }

                $payment->midtrans_transaction_id = $transactionId ?? $payment->midtrans_transaction_id;
                $payment->payment_type = $paymentType ?? $payment->payment_type ?? 'other';
                $payment->midtrans_response = $statusArray;

                if (in_array($transactionStatus, ['settlement', 'capture']) && ($fraudStatus === 'accept' || !$fraudStatus)) {
                    $payment->status = 'settlement';
                    $payment->save();
                    $order->update(['status' => 'paid']);

                    try {
                        \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\PaymentSuccessMail($order));
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning('Failed sending payment success email: ' . $e->getMessage());
                    }
                } elseif (in_array($transactionStatus, ['expire', 'cancel', 'deny'])) {
                    $payment->status = $transactionStatus;
                    $payment->save();
                    $order->update(['status' => 'cancelled']);
                    foreach ($order->items as $item) {
                        $item->product->increment('stock', $item->quantity);
                    }
                } else {
                    $payment->status = 'pending';
                    $payment->save();
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info("Could not sync Midtrans status for order {$order->order_number}: " . $e->getMessage());
        }

        return $order->fresh(['items.product.primaryImage', 'payment']);
    }
}
