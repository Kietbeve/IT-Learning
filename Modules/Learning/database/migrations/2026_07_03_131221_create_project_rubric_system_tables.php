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
        // Tạo bảng project_rubric_criteria - chứa các tiêu chí chấm điểm cho project
        Schema::create('project_rubric_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->string('title'); // Tên tiêu chí
            $table->text('description')->nullable(); // Mô tả chi tiết
            $table->decimal('max_points', 5, 2)->default(0); // Điểm tối đa cho tiêu chí này
            $table->integer('sort_order')->default(0); // Thứ tự hiển thị
            $table->timestamps();
            
            $table->index(['project_id', 'sort_order']);
        });

        // Tạo bảng submission_rubric_scores - chứa điểm cho từng tiêu chí của submission
        Schema::create('submission_rubric_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('submission_id')->constrained('project_submissions')->onDelete('cascade');
            $table->foreignId('rubric_criteria_id')->constrained('project_rubric_criteria')->onDelete('cascade');
            $table->decimal('score', 5, 2)->default(0); // Điểm thực tế đạt được
            $table->text('notes')->nullable(); // Ghi chú của giáo viên cho tiêu chí này
            $table->timestamps();
            
            $table->index('submission_id');
            $table->unique(['submission_id', 'rubric_criteria_id']); // Mỗi submission chỉ có 1 score cho mỗi criteria
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_rubric_scores');
        Schema::dropIfExists('project_rubric_criteria');
    }
};
