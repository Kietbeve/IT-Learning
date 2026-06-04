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
        Schema::create('roadmap_sections', function (Blueprint $table) {
        $table->id();

        $table->foreignId('roadmap_id')
            ->constrained('roadmaps')
            ->cascadeOnDelete();

        $table->string('title');

        $table->integer('sort_order')
            ->default(0);

        $table->timestamps();

        $table->index('roadmap_id');
        $table->index('sort_order');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_sections');
    }
};
