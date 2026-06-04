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
        Schema::create('roadmap_lessons', function (Blueprint $table) {
        $table->id();

        $table->foreignId('roadmap_id')
            ->constrained('roadmaps')
            ->cascadeOnDelete();

        $table->foreignId('section_id')
            ->nullable()
            ->constrained('roadmap_sections')
            ->nullOnDelete();

        $table->string('title');

        $table->string('slug')
            ->unique();

        $table->enum('lesson_type', [
            'text',
            'video',
            'document',
            'exam',
            'project',
        ])->default('text');

        $table->longText('content')
            ->nullable();

        $table->string('video_url')
            ->nullable();

        $table->foreignId('document_id')
            ->nullable()
            ->constrained('documents')
            ->nullOnDelete();

        $table->foreignId('exam_id')
            ->nullable()
            ->constrained('exams')
            ->nullOnDelete();

        $table->foreignId('project_id')
            ->nullable()
            ->constrained('projects')
            ->nullOnDelete();

        $table->boolean('is_preview')
            ->default(false);

        $table->boolean('is_required')
            ->default(true);

        $table->boolean('is_published')
            ->default(true);

        $table->integer('sort_order')
            ->default(0);

        $table->timestamps();

        $table->index('roadmap_id');
        $table->index('section_id');
        $table->index('lesson_type');
        $table->index('sort_order');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_lessons');
    }
};
