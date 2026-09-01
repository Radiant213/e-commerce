<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * List all wishlisted products for authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $wishlists = Wishlist::where('user_id', $request->user()->id)
            ->with(['product' => function ($query) {
                $query->with(['primaryImage', 'category']);
            }])
            ->latest()
            ->paginate(12);

        return response()->json($wishlists);
    }

    /**
     * Toggle wishlist item (add if not exists, remove if exists).
     */
    public function toggle(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = $request->user()->id;
        $productId = $validated['product_id'];

        $existing = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();
            return response()->json([
                'message' => 'Produk dihapus dari wishlist.',
                'is_wishlisted' => false,
                'product_id' => $productId,
            ]);
        }

        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json([
            'message' => 'Produk ditambahkan ke wishlist!',
            'is_wishlisted' => true,
            'product_id' => $productId,
        ], 201);
    }

    /**
     * Remove product from wishlist.
     */
    public function destroy(Request $request, int $productId): JsonResponse
    {
        Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $productId)
            ->delete();

        return response()->json([
            'message' => 'Produk dihapus dari wishlist.',
            'is_wishlisted' => false,
            'product_id' => $productId,
        ]);
    }
}
