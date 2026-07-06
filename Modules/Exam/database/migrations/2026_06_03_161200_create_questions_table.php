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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('author_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->foreignId('subject_id')
                ->nullable()
                ->constrained('subjects')
                ->nullOnDelete();

            $table->longText('content');

            $table->longText('answer_text')
                ->nullable();

            $table->longText('explanation')
                ->nullable();

            $table->enum('difficulty', [
                'easy',
                'medium',
                'hard',
            ])->default('medium');

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->boolean('is_shared')
                ->default(false);

            $table->enum('type', [
                'single_choice',
                'multiple_choice',
                'essay',
            ]);

            $table->text('rejected_reason')
                ->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('author_id');
            $table->index('category_id');
            $table->index('difficulty');
            $table->index('status');
            $table->index('type');
            $table->index('reviewed_by');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
