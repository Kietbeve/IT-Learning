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
        Schema::create('projects', function (Blueprint $table) {
        $table->id();

        $table->foreignId('roadmap_id')
            ->constrained('roadmaps')
            ->cascadeOnDelete();

        $table->foreignId('section_id')
            ->nullable()
            ->constrained('roadmap_sections')
            ->nullOnDelete();

        $table->string('title');

        $table->longText('description');

        $table->string('starter_code_url')
            ->nullable();

        $table->timestamp('deadline_at')
            ->nullable();

        $table->unsignedTinyInteger('max_resubmissions')
            ->default(3);

        $table->integer('sort_order')
            ->default(0);

        $table->timestamps();

        $table->index('roadmap_id');
        $table->index('section_id');
        $table->index('deadline_at');
        $table->index('sort_order');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
