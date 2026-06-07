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
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->decimal('amount', 15, 2);
            $table->string('bank_name');
            $table->string('bank_account_number');
            $table->string('bank_account_name');

            $table->string('status')->default('pending'); // pending | completed | rejected
            $table->string('receipt_image')->nullable();
            $table->text('note')->nullable();

            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('processed_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_requests');
    }
};
