<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('video_path', 1024)->nullable()->after('description');
            $table->string('video_source_type', 32)->default('upload')->after('video_path');
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->string('media_type', 32)->default('image')->after('image_path');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['video_path', 'video_source_type']);
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropColumn('media_type');
        });
    }
};
