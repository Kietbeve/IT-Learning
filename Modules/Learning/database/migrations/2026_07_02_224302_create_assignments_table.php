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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique();
            
            // Assignment belongs to a lesson
            $table->foreignId('lesson_id')
                ->constrained('roadmap_lessons')
                ->onDelete('cascade');
            
            // Created by admin/instructor
            $table->foreignId('created_by')
                ->constrained('users')
                ->onDelete('cascade');
            
            // Basic info
            $table->string('title');
            $table->text('description');
            $table->text('instructions')->nullable();
            $table->text('requirements')->nullable(); // JSON array of requirements
            
            // Submission settings
            $table->enum('submission_type', ['file', 'github', 'both'])->default('both');
            $table->string('allowed_file_types')->nullable(); // zip,pdf,png,jpg
            $table->integer('max_file_size_mb')->default(100);
            
            // Deadline settings
            $table->integer('deadline_days')->default(3); // Days from assignment start
            $table->boolean('send_reminder')->default(true);
            $table->integer('reminder_hours')->default(24); // Hours before deadline
            
            // Grading settings
            $table->integer('max_score')->default(100);
            $table->boolean('has_rubric')->default(true);
            $table->enum('grading_type', ['points', 'pass_fail'])->default('points');
            
            // Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('lesson_id');
            $table->index('created_by');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
