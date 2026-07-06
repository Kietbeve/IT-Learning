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
        Schema::create('assignment_attachments', function (Blueprint $table) {
            $table->id();
            
            // Attachment có thể thuộc về Assignment hoặc Submission
            $table->foreignId('assignment_id')
                ->nullable()
                ->constrained('assignments')
                ->cascadeOnDelete()
                ->comment('File đính kèm từ giảng viên');
            
            $table->foreignId('submission_id')
                ->nullable()
                ->constrained('assignment_submissions')
                ->cascadeOnDelete()
                ->comment('File đính kèm từ học viên');
            
            $table->foreignId('uploaded_by')
                ->constrained('users')
                ->cascadeOnDelete();
            
            // File information
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->comment('Size in bytes');
            $table->string('mime_type')->nullable();
            
            // Metadata
            $table->text('description')->nullable();
            $table->integer('download_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('assignment_id');
            $table->index('submission_id');
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_attachments');
    }
};
