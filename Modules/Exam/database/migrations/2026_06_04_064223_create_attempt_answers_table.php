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
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('attempt_id')
                ->constrained('exam_attempts')
                ->cascadeOnDelete();

            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->json('selected_option_ids')
                ->nullable();

            $table->longText('answer_text')
                ->nullable();
            //chuyển đổi sang sử dụng status thay cho is_correct
            $table->enum('status', [
                'pending',
                'correct',
                'incorrect',
            ])->default('pending');

             // Ghi chú/nhận xét của giáo viên (tùy chọn)
            $table->text('teacher_comment')->nullable();

            $table->decimal('score', 8, 2)
                ->default(0);

            $table->boolean('is_correct')
                ->nullable();

            $table->timestamp('answered_at')
                ->nullable();

            $table->timestamps();

            $table->index('attempt_id');
            $table->index('question_id');
            $table->index('status');
            $table->index('is_correct');
            $table->index('answered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
