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
        Schema::create('exam_tag_maps', function (Blueprint $table) {
            $table->foreignId('exam_id')
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->foreignId('tag_id')
                ->constrained('tags')
                ->cascadeOnDelete();

            $table->primary([
                'exam_id',
                'tag_id',
            ]);

            $table->index('tag_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_tag_maps');
    }
};
