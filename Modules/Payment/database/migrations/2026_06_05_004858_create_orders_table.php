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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();

            $table->decimal('total_amount', 15, 2);
            $table->string('payment_status')->default('pending'); // pending | paid | failed | refunded
            $table->string('order_status')->default('pending'); // pending | completed | canceled

            $table->timestamp('paid_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->string('download_token')->unique()->nullable();
            $table->tinyInteger('guest_download_limit')->default(5);
            $table->tinyInteger('guest_download_count')->default(0);

            $table->timestamps();

            $table->index('order_code');
            $table->index('user_id');
            $table->index('guest_email');
            $table->index('payment_status');
            $table->index('order_status');
            $table->index('paid_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
