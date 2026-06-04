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
        Schema::create('question_tag_maps', function (Blueprint $table) {
            $table->foreignId('question_id')
                ->constrained('questions')
                ->cascadeOnDelete();

            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->primary([
                'question_id',
                'tag_id',
            ]);

            $table->index('tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_tag_maps');
    }
};
