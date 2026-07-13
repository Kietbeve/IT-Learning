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
        Schema::create('lesson_quiz_attempts', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('quiz_id')
                ->constrained('lesson_quizzes')
                ->cascadeOnDelete();
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->foreignId('lesson_id')
                ->constrained('roadmap_lessons')
                ->cascadeOnDelete();
            
            $table->timestamp('started_at');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            
            $table->integer('total_questions')->default(0);
            $table->integer('answered_questions')->default(0);
            $table->integer('correct_answers')->default(0);
            $table->integer('wrong_answers')->default(0);
            
            $table->decimal('score', 8, 2)->default(0)->comment('Điểm đạt được');
            $table->decimal('max_score', 8, 2)->default(0)->comment('Điểm tối đa');
            $table->decimal('percent_score', 5, 2)->default(0)->comment('Điểm phần trăm');
            
            $table->boolean('is_passed')->default(false);
            
            $table->enum('status', ['in_progress', 'submitted', 'auto_submitted', 'completed'])
                ->default('in_progress');
            
            $table->integer('attempt_number')->default(1)->comment('Lần làm thứ mấy');
            
            $table->timestamps();
            
            $table->index('quiz_id');
            $table->index('user_id');
            $table->index('lesson_id');
            $table->index(['user_id', 'quiz_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_quiz_attempts');
    }
};
