<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('snap_token')->nullable();
            $table->string('payment_type')->nullable();
            $table->enum('status', [
                'pending',
                'settlement',
                'capture',
                'expire',
                'cancel',
                'deny',
                'refund',
            ])->default('pending');
            $table->decimal('gross_amount', 12, 2);
            $table->json('midtrans_response')->nullable();
            $table->timestamps();

            $table->index('midtrans_transaction_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
