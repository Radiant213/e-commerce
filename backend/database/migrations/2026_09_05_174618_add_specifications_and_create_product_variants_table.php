<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add specifications to products table
        if (!Schema::hasColumn('products', 'specifications')) {
            Schema::table('products', function (Blueprint $table) {
                $table->json('specifications')->nullable()->after('description');
            });
        }

        // 2. Create product_variants table
        if (!Schema::hasTable('product_variants')) {
            Schema::create('product_variants', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->string('sku')->nullable()->index();
                $table->decimal('price', 12, 2)->nullable();
                $table->decimal('sale_price', 12, 2)->nullable();
                $table->integer('stock')->default(0);
                $table->string('image_path', 1024)->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(1);
                $table->timestamps();
            });
        }

        // 3. Add variant_id to cart_items table
        if (!Schema::hasColumn('cart_items', 'variant_id')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        }

        // 4. Add variant_id and variant_name to order_items table
        if (!Schema::hasColumn('order_items', 'variant_id')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('order_items', 'variant_name')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('variant_name')->nullable()->after('product_name');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (Schema::hasColumn('order_items', 'variant_id')) {
                    $table->dropForeign(['variant_id']);
                    $table->dropColumn('variant_id');
                }
                if (Schema::hasColumn('order_items', 'variant_name')) {
                    $table->dropColumn('variant_name');
                }
            });
        }

        if (Schema::hasTable('cart_items') && Schema::hasColumn('cart_items', 'variant_id')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropForeign(['variant_id']);
                $table->dropColumn('variant_id');
            });
        }

        Schema::dropIfExists('product_variants');

        if (Schema::hasTable('products') && Schema::hasColumn('products', 'specifications')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('specifications');
            });
        }
    }
};

