<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        protected CartService $cartService
    ) {}

    /**
     * View user's cart.
     */
    public function index(Request $request): JsonResponse
    {
        $cart = $this->cartService->getCartWithItems($request->user()->id);

        if (!$cart) {
            return response()->json([
                'cart' => null,
                'items' => [],
                'total' => 0,
                'total_items' => 0,
            ]);
        }

        return response()->json([
            'cart' => $cart,
            'items' => $cart->items,
            'total' => $cart->total,
            'total_items' => $cart->total_items,
        ]);
    }

    /**
     * Add item to cart.
     */
    public function addItem(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        try {
            $item = $this->cartService->addItem(
                $request->user()->id,
                $validated['product_id'],
                $validated['quantity'] ?? 1
            );

            return response()->json([
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'item' => $item,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Update quantity of a cart item.
     */
    public function updateItem(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        try {
            $item = $this->cartService->updateItem(
                $request->user()->id,
                $id,
                $validated['quantity']
            );

            return response()->json([
                'message' => 'Keranjang berhasil diperbarui.',
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Remove item from cart.
     */
    public function removeItem(Request $request, int $id): JsonResponse
    {
        $this->cartService->removeItem($request->user()->id, $id);

        return response()->json([
            'message' => 'Item berhasil dihapus dari keranjang.',
        ]);
    }

    /**
     * Clear user's entire cart.
     */
    public function clear(Request $request): JsonResponse
    {
        $this->cartService->clearCart($request->user()->id);

        return response()->json([
            'message' => 'Keranjang berhasil dikosongkan.',
        ]);
    }
}
