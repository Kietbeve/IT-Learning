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
        Schema::create('assignment_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique();
            
            // Belongs to assignment and student
            $table->foreignId('assignment_id')
                ->constrained('assignments')
                ->onDelete('cascade');
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            
            // Submission content
            $table->string('github_url')->nullable();
            $table->string('live_demo_url')->nullable();
            $table->string('file_path')->nullable(); // Stored file path
            $table->string('file_name')->nullable();
            $table->text('notes')->nullable(); // Student's notes
            
            // Submission tracking
            $table->integer('attempt_number')->default(1);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('deadline')->nullable(); // Calculated from assignment
            $table->boolean('is_late')->default(false);
            $table->integer('days_late')->default(0);
            
            // Grading
            $table->enum('status', [
                'draft',
                'submitted',
                'in_review',
                'graded',
                'needs_revision'
            ])->default('draft');
            
            $table->foreignId('graded_by')->nullable()
                ->constrained('users')
                ->onDelete('set null');
            
            $table->integer('score')->nullable();
            $table->text('feedback')->nullable();
            $table->timestamp('graded_at')->nullable();
            
            // Notification tracking
            $table->boolean('reminder_sent')->default(false);
            $table->timestamp('reminder_sent_at')->nullable();
            $table->boolean('deadline_warning_sent')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('assignment_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('graded_by');
            $table->index('is_late');
            $table->index(['assignment_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_submissions');
    }
};
