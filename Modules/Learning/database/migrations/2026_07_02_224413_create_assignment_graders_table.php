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
        Schema::create('assignment_graders', function (Blueprint $table) {
            $table->id();
            
            // Assignment to be graded
            $table->foreignId('assignment_id')
                ->constrained('assignments')
                ->onDelete('cascade');
            
            // Grader (CTV or Admin)
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            
            // Auto-assign settings
            $table->boolean('is_active')->default(true);
            $table->integer('max_assignments')->default(50); // Max submissions to grade
            $table->integer('current_count')->default(0); // Current assigned count
            
            $table->timestamps();
            
            // Indexes
            $table->index('assignment_id');
            $table->index('user_id');
            $table->index('is_active');
            $table->unique(['assignment_id', 'user_id']); // One grader per assignment
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_graders');
    }
};
