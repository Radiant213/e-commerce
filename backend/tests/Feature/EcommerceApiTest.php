<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EcommerceApiTest extends TestCase
{
    public function test_can_list_categories(): void
    {
        $response = $this->getJson('/api/categories');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_can_list_products_and_filter(): void
    {
        $response = $this->getJson('/api/products');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'total', 'current_page']);
    }

    public function test_can_get_featured_and_best_sellers(): void
    {
        $this->getJson('/api/products/featured')->assertStatus(200);
        $this->getJson('/api/products/best-sellers')->assertStatus(200);
        $this->getJson('/api/products/new-arrivals')->assertStatus(200);
    }

    public function test_auth_and_protected_cart_wishlist_flow(): void
    {
        // 1. Login
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $loginResponse->assertStatus(200);
        $token = $loginResponse->json('token');
        $this->assertNotEmpty($token);

        // 2. Fetch authenticated user profile
        $userResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/user');
        $userResponse->assertStatus(200);

        // 3. Add to Cart
        $product = Product::first();
        $addToCart = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/cart/items', [
                'product_id' => $product->id,
                'quantity' => 2,
            ]);
        $addToCart->assertStatus(201);

        // 4. View Cart
        $cartResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/cart');
        $cartResponse->assertStatus(200);
        $this->assertGreaterThan(0, $cartResponse->json('total'));

        // 5. Toggle Wishlist
        $wishlistToggle = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/wishlist/toggle', [
                'product_id' => $product->id,
            ]);
        $wishlistToggle->assertStatus(200);

        // 6. View Wishlist
        $wishlistResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/wishlist');
        $wishlistResponse->assertStatus(200);

        // 7. Add Review
        $reviewResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/products/{$product->id}/reviews", [
                'rating' => 5,
                'comment' => 'Produk sangat memuaskan dan berkualitas tinggi!',
            ]);
        $reviewResponse->assertStatus(201);
    }

    public function test_advanced_search_and_filter(): void
    {
        $response = $this->getJson('/api/products?search=Sony&min_price=1000000&max_price=10000000&sort_by=price&sort_dir=asc');
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }
}
