<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected CartService $cartService,
        protected MidtransService $midtransService,
    ) {}

    /**
     * Create order from user's cart.
     */
    public function createOrder(int $userId, array $shippingData): array
    {
        return DB::transaction(function () use ($userId, $shippingData) {
            $cart = Cart::where('user_id', $userId)
                ->with(['items.product'])
                ->firstOrFail();

            if ($cart->items->isEmpty()) {
                throw new \Exception('Keranjang belanja kosong.');
            }

            // Validate stock availability
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception(
                        "Stok {$item->product->name} tidak mencukupi. Tersedia: {$item->product->stock}"
                    );
                }
            }

            // Calculate totals
            $subtotal = $cart->items->sum(function ($item) {
                return $item->subtotal;
            });
            $shippingCost = $shippingData['shipping_cost'] ?? 0;
            $total = $subtotal + $shippingCost;

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'status' => 'pending',
                'shipping_name' => $shippingData['shipping_name'],
                'shipping_phone' => $shippingData['shipping_phone'],
                'shipping_address' => $shippingData['shipping_address'],
                'shipping_city' => $shippingData['shipping_city'] ?? null,
                'shipping_postal_code' => $shippingData['shipping_postal_code'] ?? null,
                'notes' => $shippingData['notes'] ?? null,
            ]);

            // Create order items & reduce stock
            foreach ($cart->items as $item) {
                $unitPrice = $item->variant?->effective_price ?? $item->product->effective_price;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant?->name,
                    'product_price' => $unitPrice,
                    'quantity' => $item->quantity,
                    'subtotal' => $unitPrice * $item->quantity,
                ]);

                // Reduce stock
                if ($item->variant) {
                    $item->variant->decrement('stock', $item->quantity);
                }
                $item->product->decrement('stock', $item->quantity);
                $item->product->increment('total_sold', $item->quantity);
            }

            // Clear cart
            $this->cartService->clearCart($userId);

            // Send order confirmation email
            try {
                \Illuminate\Support\Facades\Mail::to($order->user->email)->send(new \App\Mail\OrderConfirmationMail($order));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('Failed sending order confirmation email: ' . $e->getMessage());
            }

            // Generate MidTrans Snap token
            $order->load(['items', 'user']);
            $snapToken = $this->midtransService->createSnapToken($order);

            return [
                'order' => $order->load(['items', 'payment']),
                'snap_token' => $snapToken,
            ];
        });
    }

    /**
     * Cancel an order.
     */
    public function cancelOrder(Order $order): Order
    {
        if (!$order->canBeCancelled()) {
            throw new \Exception('Order tidak dapat dibatalkan.');
        }

        DB::transaction(function () use ($order) {
            // Restore stock
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
                $item->product->decrement('total_sold', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);

            if ($order->payment) {
                $order->payment->update(['status' => 'cancel']);
            }
        });

        return $order->fresh(['items', 'payment']);
    }
}
