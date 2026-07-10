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
        Schema::create('document_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // FK xuyên Module (Đơn hàng). Sẽ tạo cứng bằng file riêng ở Payment Module hoặc để nullable mềm
            $table->unsignedBigInteger('order_item_id')->nullable();

            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('source'); // free | paid | guest
            $table->timestamp('downloaded_at')->useCurrent();

            $table->index('document_id');
            $table->index('user_id');
            $table->index('order_item_id');
            $table->index('ip_address');
            $table->index('source');
            $table->index('downloaded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_downloads');
    }
};
