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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();

            $table->string('public_id')->unique();

            $table->foreignId('author_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->cascadeOnDelete();

            $table->string('title');

            $table->string('slug')
                ->unique();

            $table->text('short_description')
                ->nullable();

            $table->longText('description')
                ->nullable();

            $table->enum('type', [
                'multiple_choice',
                'essay',
                'hybrid',
            ]);

            $table->enum('mode', [
                'practice',
                'official',
            ]);

            $table->integer('duration_minutes');

            $table->decimal('pass_percent', 5, 2)
                ->default(50);

            $table->enum('visibility', [
                'public',
                'private',
            ])->default('public');

            $table->enum('status', [
                'draft',
                'pending',
                'approved',
                'rejected',
            ])->default('pending');

            $table->text('rejected_reason')
                ->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')
                ->nullable();

            $table->timestamp('publish_at')
                ->nullable();

            $table->unsignedInteger('attempt_count')
                ->default(0);

            $table->timestamps();

            $table->softDeletes();

            $table->index('public_id');
            $table->index('author_id');
            $table->index('category_id');
            $table->index('visibility');
            $table->index('status');
            $table->index('reviewed_by');
            $table->index('publish_at');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
