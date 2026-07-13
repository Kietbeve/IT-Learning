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
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('lesson_quiz_answers');
        Schema::dropIfExists('lesson_quiz_attempts');
        Schema::dropIfExists('lesson_quiz_questions');
        Schema::dropIfExists('lesson_quizzes');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 
    }
};
