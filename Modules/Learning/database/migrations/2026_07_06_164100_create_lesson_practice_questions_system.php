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
        // Bảng lesson_practice_questions - các câu hỏi ôn tập cho bài học
        Schema::create('lesson_practice_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')
                ->constrained('roadmap_lessons')
                ->cascadeOnDelete();
            
            $table->enum('type', ['single_choice', 'multiple_choice', 'true_false', 'fill_blank'])
                ->default('single_choice')
                ->comment('Loại câu hỏi');
            
            $table->text('question_text')->comment('Nội dung câu hỏi');
            
            // JSON array chứa các đáp án
            // Format: [{"key": "A", "text": "...", "is_correct": true}, ...]
            $table->json('options')->nullable()->comment('Các đáp án (null cho fill_blank)');
            
            // Đáp án đúng cho câu hỏi điền khuyết
            $table->string('correct_answer')->nullable()->comment('Đáp án đúng cho fill_blank');
            
            $table->text('explanation')->nullable()->comment('Giải thích đáp án');
            
            $table->text('hint')->nullable()->comment('Gợi ý cho học viên');
            
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('easy')
                ->comment('Độ khó');
            
            $table->integer('sort_order')->default(0);
            
            $table->boolean('is_active')->default(true)->comment('Hiển thị câu hỏi');
            
            $table->timestamps();
            
            $table->index('lesson_id');
            $table->index(['lesson_id', 'sort_order']);
            $table->index(['lesson_id', 'is_active']);
        });

        // Bảng lesson_practice_answers - lưu câu trả lời của học viên
        Schema::create('lesson_practice_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')
                ->constrained('lesson_practice_questions')
                ->cascadeOnDelete();
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            // Câu trả lời của học viên (JSON cho multiple choice, text cho fill_blank)
            $table->text('user_answer')->comment('Câu trả lời của học viên');
            
            $table->boolean('is_correct')->comment('Đúng hay sai');
            
            $table->integer('attempt_count')->default(1)->comment('Số lần thử');
            
            $table->timestamp('answered_at')->useCurrent();
            
            $table->timestamps();
            
            $table->index('question_id');
            $table->index('user_id');
            $table->index(['user_id', 'question_id']);
            $table->index(['question_id', 'is_correct']);
        });

        // Bảng lesson_practice_progress - theo dõi tiến độ ôn tập của học viên
        Schema::create('lesson_practice_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')
                ->constrained('roadmap_lessons')
                ->cascadeOnDelete();
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->integer('total_questions')->default(0)->comment('Tổng số câu hỏi');
            $table->integer('completed_questions')->default(0)->comment('Số câu đã làm');
            $table->integer('correct_answers')->default(0)->comment('Số câu đúng');
            
            $table->decimal('accuracy_rate', 5, 2)->default(0)->comment('Tỷ lệ chính xác (%)');
            
            $table->timestamp('last_practiced_at')->nullable()->comment('Lần ôn tập gần nhất');
            
            $table->timestamps();
            
            $table->unique(['lesson_id', 'user_id']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_practice_progress');
        Schema::dropIfExists('lesson_practice_answers');
        Schema::dropIfExists('lesson_practice_questions');
    }
};
