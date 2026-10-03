<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('popups') && ! Schema::hasColumn('popups', 'show_on')) {
            Schema::table('popups', function (Blueprint $table) {
                $table->string('show_on')->default('all')->after('show_once_per_session');
            });
        }

        // Migrate legacy banner placement from 'homepage' to 'promo_strip'
        if (Schema::hasTable('banners')) {
            DB::table('banners')
                ->where('placement', 'homepage')
                ->update(['placement' => 'promo_strip']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('popups') && Schema::hasColumn('popups', 'show_on')) {
            Schema::table('popups', function (Blueprint $table) {
                $table->dropColumn('show_on');
            });
        }
    }
};
