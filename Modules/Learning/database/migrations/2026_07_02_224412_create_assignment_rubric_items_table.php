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
        Schema::create('assignment_rubric_items', function (Blueprint $table) {
            $table->id();
            
            // Belongs to rubric
            $table->foreignId('rubric_id')
                ->constrained('assignment_rubrics')
                ->onDelete('cascade');
            
            // Belongs to submission for grading
            $table->foreignId('submission_id')
                ->nullable()
                ->constrained('assignment_submissions')
                ->onDelete('cascade');
            
            $table->string('criterion'); // What is being graded
            $table->integer('max_points');
            $table->integer('points_earned')->nullable();
            $table->text('feedback')->nullable();
            $table->boolean('is_checked')->default(false);
            
            $table->timestamps();
            
            // Indexes
            $table->index('rubric_id');
            $table->index('submission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_rubric_items');
    }
};
