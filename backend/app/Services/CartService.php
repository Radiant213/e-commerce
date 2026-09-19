<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class CartService
{
    /**
     * Get or create cart for user.
     */
    public function getOrCreateCart(int $userId): Cart
    {
        return Cart::firstOrCreate(['user_id' => $userId]);
    }

    /**
     * Add item to cart.
     */
    public function addItem(int $userId, int $productId, int $quantity = 1, ?int $variantId = null): CartItem
    {
        $cart = $this->getOrCreateCart($userId);
        $product = Product::findOrFail($productId);

        if (!$product->is_active) {
            throw new \Exception('Produk tidak tersedia.');
        }

        $maxStock = $product->stock;
        if ($variantId) {
            $variant = ProductVariant::where('product_id', $productId)->findOrFail($variantId);
            if (!$variant->is_active) {
                throw new \Exception('Variasi produk ini sedang tidak aktif.');
            }
            $maxStock = $variant->stock;
        }

        if ($maxStock < 1) {
            throw new \Exception('Stok produk atau variasi telah habis.');
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->where('variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $newQty = $cartItem->quantity + $quantity;
            if ($newQty > $maxStock) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$maxStock}");
            }
            $cartItem->update(['quantity' => $newQty]);
        } else {
            if ($quantity > $maxStock) {
                throw new \Exception("Stok tidak mencukupi. Tersedia: {$maxStock}");
            }
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'variant_id' => $variantId,
                'quantity' => $quantity,
            ]);
        }

        return $cartItem->load(['product.primaryImage', 'variant']);
    }

    /**
     * Update cart item quantity.
     */
    public function updateItem(int $userId, int $cartItemId, int $quantity): CartItem
    {
        $cart = $this->getOrCreateCart($userId);
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('id', $cartItemId)
            ->firstOrFail();

        if ($quantity > $cartItem->product->stock) {
            throw new \Exception("Stok tidak mencukupi. Tersedia: {$cartItem->product->stock}");
        }

        if ($quantity <= 0) {
            $cartItem->delete();
            return $cartItem;
        }

        $cartItem->update(['quantity' => $quantity]);
        return $cartItem->load('product.primaryImage');
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(int $userId, int $cartItemId): void
    {
        $cart = $this->getOrCreateCart($userId);
        CartItem::where('cart_id', $cart->id)
            ->where('id', $cartItemId)
            ->delete();
    }

    /**
     * Clear all items from cart.
     */
    public function clearCart(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)->first();
        if ($cart) {
            $cart->items()->delete();
        }
    }

    /**
     * Get cart with all items and products.
     */
    public function getCartWithItems(int $userId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->with(['items.product.primaryImage', 'items.product.category', 'items.variant'])
            ->first();
    }
}
