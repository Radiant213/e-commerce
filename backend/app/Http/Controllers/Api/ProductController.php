<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * List products with search, filter, and sort.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::query()
            ->active()
            ->with(['primaryImage', 'category'])
            ->withCount('wishlists');

        // Search
        $query->search($request->input('search'));

        // Filters
        $query->byCategory($request->input('category_id'));
        $query->priceRange(
            $request->input('min_price') ? (float) $request->input('min_price') : null,
            $request->input('max_price') ? (float) $request->input('max_price') : null,
        );
        $query->minRating($request->input('min_rating'));

        if ($request->has('is_featured')) {
            $query->featured();
        }

        // Sort
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $allowedSorts = ['created_at', 'price', 'name', 'avg_rating', 'total_sold'];

        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
        }

        $perPage = min((int) $request->input('per_page', 12), 50);
        $products = $query->paginate($perPage);

        // If user is authenticated, check wishlist status
        if ($request->user()) {
            $wishlistedIds = $request->user()
                ->wishlists()
                ->pluck('product_id')
                ->toArray();

            $products->getCollection()->transform(function ($product) use ($wishlistedIds) {
                $product->is_wishlisted = in_array($product->id, $wishlistedIds);
                return $product;
            });
        }

        return response()->json($products);
    }

    /**
     * Get single product by slug.
     */
    public function show(Request $request, string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with([
                'images',
                'category',
                'reviews' => fn($q) => $q->with('user')->latest()->take(10),
            ])
            ->withCount(['reviews', 'wishlists'])
            ->firstOrFail();

        // Check wishlist status for authenticated user
        $product->is_wishlisted = false;
        if ($request->user()) {
            $product->is_wishlisted = $request->user()
                ->wishlists()
                ->where('product_id', $product->id)
                ->exists();
        }

        // Get related products (same category)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->with('primaryImage')
            ->inRandomOrder()
            ->take(8)
            ->get();

        return response()->json([
            'product' => $product,
            'related_products' => $relatedProducts,
        ]);
    }

    /**
     * Get featured products for homepage.
     */
    public function featured(): JsonResponse
    {
        $products = Product::active()
            ->featured()
            ->with('primaryImage')
            ->orderBy('total_sold', 'desc')
            ->take(8)
            ->get();

        return response()->json(['data' => $products]);
    }

    /**
     * Get best selling products.
     */
    public function bestSellers(): JsonResponse
    {
        $products = Product::active()
            ->with('primaryImage')
            ->orderBy('total_sold', 'desc')
            ->take(8)
            ->get();

        return response()->json(['data' => $products]);
    }

    /**
     * Get new arrivals.
     */
    public function newArrivals(): JsonResponse
    {
        $products = Product::active()
            ->with('primaryImage')
            ->latest()
            ->take(8)
            ->get();

        return response()->json(['data' => $products]);
    }
}
