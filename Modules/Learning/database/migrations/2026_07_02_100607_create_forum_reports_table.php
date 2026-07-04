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
        Schema::create('forum_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Người báo cáo
            $table->morphs('reportable'); // reportable_id, reportable_type (thread hoặc post)
            $table->string('reason', 50); // spam, inappropriate, harassment, other
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->enum('status', ['pending', 'reviewed', 'resolved', 'dismissed'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null'); // Admin xử lý
            $table->text('admin_note')->nullable(); // Ghi chú của admin
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['reportable_id', 'reportable_type']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_reports');
    }
};
