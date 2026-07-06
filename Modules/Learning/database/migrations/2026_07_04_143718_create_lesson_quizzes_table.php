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
        Schema::create('lesson_quizzes', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('lesson_id')
                ->constrained('roadmap_lessons')
                ->cascadeOnDelete();
            
            $table->string('title');
            
            $table->text('description')->nullable();
            
            // Cấu hình quiz
            $table->integer('duration_minutes')->nullable()->comment('Thời gian làm bài (phút), null = không giới hạn');
            $table->integer('pass_score')->default(60)->comment('Điểm đạt tối thiểu (%)');
            $table->integer('max_attempts')->nullable()->comment('Số lần làm tối đa, null = không giới hạn');
            $table->boolean('shuffle_questions')->default(false)->comment('Trộn câu hỏi');
            $table->boolean('shuffle_options')->default(false)->comment('Trộn đáp án');
            $table->boolean('show_correct_answers')->default(true)->comment('Hiển thị đáp án đúng sau khi nộp');
            $table->boolean('allow_review')->default(true)->comment('Cho phép xem lại bài làm');
            
            $table->boolean('is_required')->default(false)->comment('Bắt buộc hoàn thành để tiếp tục');
            $table->boolean('is_published')->default(true);
            
            $table->timestamps();
            
            $table->index('lesson_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_quizzes');
    }
};
