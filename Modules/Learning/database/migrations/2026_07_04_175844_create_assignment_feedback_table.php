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
        Schema::create('assignment_feedback', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('submission_id')
                ->constrained('assignment_submissions')
                ->cascadeOnDelete();
            
            $table->foreignId('rubric_item_id')
                ->nullable()
                ->constrained('assignment_rubric_items')
                ->nullOnDelete()
                ->comment('Feedback cho tiêu chí cụ thể');
            
            $table->foreignId('grader_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Giảng viên chấm bài');
            
            // Feedback content
            $table->text('comment');
            $table->decimal('score', 5, 2)->nullable()->comment('Điểm cho tiêu chí này');
            
            // Feedback type
            $table->enum('feedback_type', ['general', 'criterion', 'inline'])->default('general');
            
            // Reference location (for inline feedback)
            $table->string('reference_file')->nullable()->comment('File được comment');
            $table->integer('reference_line')->nullable()->comment('Dòng được comment');
            
            // Status
            $table->boolean('is_resolved')->default(false);
            $table->timestamp('resolved_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('submission_id');
            $table->index('rubric_item_id');
            $table->index('grader_id');
            $table->index('feedback_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_feedback');
    }
};
