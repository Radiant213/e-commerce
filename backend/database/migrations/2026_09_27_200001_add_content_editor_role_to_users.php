<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Change role enum to include 'content_editor' for CMS access.
     */
    public function up(): void
    {
        // MySQL enum modification — add 'content_editor' role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin', 'content_editor') DEFAULT 'customer'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert content_editor users to customer before removing the enum value
        DB::table('users')->where('role', 'content_editor')->update(['role' => 'customer']);
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('customer', 'admin') DEFAULT 'customer'");
    }
};
