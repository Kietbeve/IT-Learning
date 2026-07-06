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
        Schema::create('lesson_quiz_answers', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('attempt_id')
                ->constrained('lesson_quiz_attempts')
                ->cascadeOnDelete();
            
            $table->foreignId('question_id')
                ->constrained('lesson_quiz_questions')
                ->cascadeOnDelete();
            
            // JSON array chứa các đáp án đã chọn (key của option)
            // VD: ["A"] hoặc ["A", "C"] cho multiple choice
            $table->json('selected_options')->nullable();
            
            $table->boolean('is_correct')->default(false);
            
            $table->decimal('score', 8, 2)->default(0)->comment('Điểm đạt được cho câu này');
            
            $table->timestamp('answered_at')->nullable();
            
            $table->timestamps();
            
            $table->index('attempt_id');
            $table->index('question_id');
            $table->index(['attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_quiz_answers');
    }
};
