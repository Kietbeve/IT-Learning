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

            $table->boolean('is_correct')
                ->nullable();

            $table->timestamp('answered_at')
                ->nullable();

            $table->timestamps();

            $table->index('attempt_id');
            $table->index('question_id');
            $table->index('is_correct');
            $table->index('answered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempt_answers');
    }
};
