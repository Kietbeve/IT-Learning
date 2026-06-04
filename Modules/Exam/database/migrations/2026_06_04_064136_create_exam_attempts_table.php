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
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('session_id')
                ->unique();

            $table->timestamp('started_at');

            $table->timestamp('submitted_at')
                ->nullable();

            $table->timestamp('expires_at');

            $table->unsignedInteger('total_questions')
                ->default(0);

            $table->unsignedInteger('correct_answers')
                ->default(0);

            $table->unsignedInteger('wrong_answers')
                ->default(0);

            $table->unsignedInteger('skipped_answers')
                ->default(0);

            $table->decimal('score', 8, 2)
                ->nullable();

            $table->decimal('percent_score', 5, 2)
                ->nullable();

            $table->boolean('is_passed')
                ->nullable();

            $table->enum('status', [
                'in_progress',
                'submitted',
                'auto_submitted',
                'canceled',
                'completed',
            ])->default('in_progress');

            $table->unsignedTinyInteger('violation_count')
                ->default(0);

            $table->timestamps();

            $table->index('exam_id');
            $table->index('user_id');
            $table->index('started_at');
            $table->index('expires_at');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
