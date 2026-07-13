<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Bảng lưu điểm số và nhận xét cuối cùng sau khi học viên hoàn thành tất cả các bước.
     * Chỉ được tạo khi tất cả các bước đã approved.
     */
    public function up(): void
    {
        Schema::create('project_final_grades', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('project_submission_id')
                ->constrained('project_submissions')
                ->cascadeOnDelete()
                ->comment('Submission tổng của project');
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Học viên được chấm điểm');
            
            // Grading
            $table->decimal('score', 5, 2)->comment('Điểm số (0-100)');
            $table->enum('grade_level', ['excellent', 'good', 'average', 'poor'])
                ->nullable()
                ->comment('Xếp loại: Xuất sắc, Tốt, Trung bình, Yếu');
            
            // Detailed feedback
            $table->text('feedback')->nullable()->comment('Nhận xét tổng quan từ giáo viên');
            $table->json('step_scores')->nullable()->comment('Điểm chi tiết từng bước (JSON)');
            
            // Grader info
            $table->foreignId('graded_by')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Người chấm điểm cuối cùng');
            
            $table->timestamp('graded_at')->useCurrent()->comment('Thời điểm chấm điểm');
            
            // Completion tracking
            $table->boolean('is_passed')->default(false)->comment('Có đạt yêu cầu không');
            $table->timestamp('completed_at')->nullable()->comment('Thời điểm hoàn thành project');
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('is_passed');
            $table->index('graded_at');
            
            // Unique: một submission chỉ có 1 final grade
            $table->unique('project_submission_id', 'unique_final_grade_per_submission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_final_grades');
    }
};
