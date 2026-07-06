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
        Schema::create('lesson_quiz_questions', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('quiz_id')
                ->constrained('lesson_quizzes')
                ->cascadeOnDelete();
            
            $table->enum('type', ['single_choice', 'multiple_choice', 'true_false'])
                ->default('single_choice');
            
            $table->text('question_text');
            
            // JSON array chứa các đáp án
            // Format: [{"key": "A", "text": "...", "is_correct": true}, ...]
            $table->json('options');
            
            $table->text('explanation')->nullable()->comment('Giải thích đáp án');
            
            $table->integer('score')->default(1)->comment('Điểm cho câu hỏi này');
            
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index('quiz_id');
            $table->index(['quiz_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_quiz_questions');
    }
};
