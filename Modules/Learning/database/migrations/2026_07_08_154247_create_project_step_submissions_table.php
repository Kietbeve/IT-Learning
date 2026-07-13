<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Bảng này lưu nội dung học viên nộp cho từng bước.
     * Một học viên có thể nộp nhiều lần (resubmit) nếu bị reject.
     */
    public function up(): void
    {
        Schema::create('project_step_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('public_id', 20)->unique()->comment('ID công khai để show cho user');
            
            // Foreign keys
            $table->foreignId('project_submission_id')
                ->constrained('project_submissions')
                ->cascadeOnDelete()
                ->comment('Submission tổng của project này');
            
            $table->foreignId('step_id')
                ->constrained('project_submission_steps')
                ->cascadeOnDelete()
                ->comment('Bước nào đang nộp');
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Học viên nộp bài');
            
            // Submission tracking
            $table->integer('submission_number')->default(1)->comment('Lần nộp thứ mấy (1 = lần đầu, 2 = lần 2...)');
            
            // Nội dung nộp bài
            $table->string('file_path')->nullable()->comment('Đường dẫn file upload (nếu type = file)');
            $table->string('file_name')->nullable()->comment('Tên file gốc');
            $table->integer('file_size_kb')->nullable()->comment('Kích thước file (KB)');
            $table->string('link_url')->nullable()->comment('Link nộp bài (Figma, GitHub, YouTube...)');
            $table->text('notes')->nullable()->comment('Ghi chú của học viên khi nộp');
            
            // Status workflow
            $table->enum('status', [
                'draft',        // Đang soạn thảo
                'submitted',    // Đã nộp, chờ review
                'under_review', // Đang được review
                'approved',     // Đã chấp nhận
                'rejected'      // Bị từ chối, cần làm lại
            ])->default('draft')->comment('Trạng thái của submission này');
            
            // Timestamps
            $table->timestamp('submitted_at')->nullable()->comment('Thời điểm nộp bài');
            $table->timestamp('reviewed_at')->nullable()->comment('Thời điểm được review');
            $table->foreignId('reviewed_by')->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Người review (Admin/CTV)');
            
            // Review result
            $table->text('feedback')->nullable()->comment('Góp ý từ reviewer');
            $table->boolean('is_current')->default(true)->comment('Có phải submission hiện tại không (latest)');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['project_submission_id', 'step_id', 'submission_number'], 'ps_sub_step_num_idx');
            $table->index(['user_id', 'status'], 'ps_user_status_idx');
            $table->index('status');
            $table->index('is_current');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_step_submissions');
    }
};
