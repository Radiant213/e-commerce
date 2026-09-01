<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Get paginated reviews for a specific product.
     */
    public function index(int $productId): JsonResponse
    {
        $reviews = Review::where('product_id', $productId)
            ->with(['user:id,name,avatar'])
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }

    /**
     * Create or update a product review.
     */
    public function store(Request $request, int $productId): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $product = Product::findOrFail($productId);
        $userId = $request->user()->id;

        // Check if user has a paid order with this product (verified purchase)
        $verifiedOrder = Order::where('user_id', $userId)
            ->whereIn('status', ['paid', 'processing', 'shipped', 'delivered'])
            ->whereHas('items', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->first();

        $review = Review::updateOrCreate(
            [
                'user_id' => $userId,
                'product_id' => $productId,
            ],
            [
                'order_id' => $verifiedOrder?->id,
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        $product->updateRatingStats();

        return response()->json([
            'message' => 'Ulasan Anda berhasil disimpan!',
            'review' => $review->load('user:id,name,avatar'),
        ], 201);
    }

    /**
     * Delete own review.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $review = Review::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $productId = $review->product_id;
        $review->delete();

        $product = Product::find($productId);
        $product?->updateRatingStats();

        return response()->json([
            'message' => 'Ulasan berhasil dihapus.',
        ]);
    }
}
