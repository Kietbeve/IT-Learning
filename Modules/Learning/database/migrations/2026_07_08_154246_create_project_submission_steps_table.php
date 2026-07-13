<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Bảng này định nghĩa các bước nộp bài cho mỗi project.
     * Admin/CTV tạo project sẽ config các bước: Phân tích yêu cầu, Thiết kế DB, UI, Code, Video
     */
    public function up(): void
    {
        Schema::create('project_submission_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete()
                ->comment('Project mà bước này thuộc về');
            
            $table->string('step_name', 100)->comment('Tên bước: Phân tích, Database, UI, Code, Video');
            $table->integer('step_order')->comment('Thứ tự bước (1-5)');
            
            // Loại nộp bài cho bước này
            $table->enum('submission_type', ['file', 'link', 'both'])
                ->default('file')
                ->comment('Loại nộp: file upload, link (figma/github), hoặc cả 2');
            
            // Instructions cho học viên
            $table->text('instructions')->nullable()->comment('Hướng dẫn chi tiết cho bước này');
            $table->text('requirements')->nullable()->comment('Yêu cầu cụ thể (JSON format)');
            
            // File upload settings (nếu submission_type = file hoặc both)
            $table->json('allowed_file_types')->nullable()->comment('Các loại file cho phép: ["doc","docx","txt","sql","zip"]');
            $table->integer('max_file_size_mb')->default(50)->comment('Kích thước file tối đa (MB)');
            
            // Link settings (nếu submission_type = link hoặc both)
            $table->string('link_placeholder')->nullable()->comment('Placeholder cho ô nhập link: "Nhập link Figma..."');
            
            // Business logic
            $table->boolean('is_required')->default(true)->comment('Bước này có bắt buộc không');
            $table->boolean('is_active')->default(true)->comment('Bước này có active không (để tắt/bật)');
            $table->integer('max_resubmissions')->default(5)->comment('Số lần nộp lại tối đa nếu bị reject');
            
            $table->timestamps();
            
            // Indexes
            $table->index(['project_id', 'step_order']);
            $table->unique(['project_id', 'step_order'], 'unique_project_step_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_submission_steps');
    }
};
