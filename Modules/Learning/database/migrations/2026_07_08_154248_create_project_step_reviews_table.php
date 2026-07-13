<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Bảng này lưu lịch sử review chi tiết cho từng lần nộp.
     * Giúp học viên xem lại các lần review trước đó.
     */
    public function up(): void
    {
        Schema::create('project_step_reviews', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('step_submission_id')
                ->constrained('project_step_submissions')
                ->cascadeOnDelete()
                ->comment('Submission nào được review');
            
            $table->foreignId('reviewer_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Người review (Admin/CTV)');
            
            // Review decision
            $table->enum('decision', ['approved', 'rejected'])
                ->comment('Kết quả: Chấp nhận hay từ chối');
            
            // Detailed feedback
            $table->text('feedback')->nullable()->comment('Nhận xét chi tiết từ reviewer');
            $table->json('review_notes')->nullable()->comment('Ghi chú bổ sung (JSON format)');
            
            // Review metadata
            $table->timestamp('reviewed_at')->useCurrent()->comment('Thời điểm review');
            $table->integer('review_duration_seconds')->nullable()->comment('Thời gian review (giây)');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['step_submission_id', 'reviewed_at']);
            $table->index('reviewer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_step_reviews');
    }
};
