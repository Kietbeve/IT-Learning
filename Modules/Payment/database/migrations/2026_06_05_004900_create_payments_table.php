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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();

            $table->string('provider'); // payos
            $table->string('transaction_code')->nullable();
            $table->string('provider_order_code')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('status')->default('pending'); // pending | success | failed | refunded
            $table->json('raw_response')->nullable();
            
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->unique(['provider', 'provider_order_code']);
            $table->index('order_id');
            $table->index('status');
            $table->index('transaction_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
