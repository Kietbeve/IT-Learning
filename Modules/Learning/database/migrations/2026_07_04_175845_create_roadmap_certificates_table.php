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
        Schema::create('roadmap_certificates', function (Blueprint $table) {
            $table->id();
            
            $table->string('certificate_number')->unique()->comment('Mã số chứng chỉ duy nhất');
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->foreignId('roadmap_id')
                ->constrained('roadmaps')
                ->cascadeOnDelete();
            
            // Certificate details
            $table->string('user_name')->comment('Tên học viên khi nhận chứng chỉ');
            $table->string('roadmap_title')->comment('Tên khóa học khi nhận chứng chỉ');
            
            // Completion details
            $table->decimal('final_score', 5, 2)->nullable()->comment('Điểm tổng kết');
            $table->decimal('completion_percent', 5, 2)->default(100);
            $table->integer('total_hours')->nullable()->comment('Tổng số giờ học');
            
            // Issue date
            $table->timestamp('issued_at');
            $table->timestamp('expires_at')->nullable()->comment('Ngày hết hạn (nếu có)');
            
            // Certificate file
            $table->string('certificate_path')->nullable()->comment('Đường dẫn file PDF chứng chỉ');
            $table->string('verification_code')->unique()->comment('Mã xác thực chứng chỉ');
            
            // Additional info
            $table->json('metadata')->nullable()->comment('Thông tin bổ sung: instructor, grade, etc.');
            
            // Status
            $table->enum('status', ['active', 'revoked', 'expired'])->default('active');
            $table->text('revoked_reason')->nullable();
            $table->timestamp('revoked_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('roadmap_id');
            $table->index('verification_code');
            $table->index('status');
            $table->index('issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_certificates');
    }
};
