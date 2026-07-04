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
        Schema::create('document_relationships', function (Blueprint $table) {
            $table->id();
            
            // Relationships - Link draft to parent (NULL if new submission)
            $table->unsignedBigInteger('parent_document_id')->nullable()->comment('NULL for new submissions, points to original for edits');
            $table->unsignedBigInteger('draft_document_id')->comment('The draft/pending document being reviewed');
            
            // Metadata
            $table->enum('relationship_type', ['new_submission', 'edit_submission'])->comment('Type of submission');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->comment('Review status');
            
            // Review tracking
            $table->text('rejected_reason')->nullable()->comment('Reason for rejection (if rejected)');
            $table->unsignedBigInteger('submitted_by')->comment('User who submitted');
            $table->unsignedBigInteger('reviewed_by')->nullable()->comment('Admin who reviewed');
            
            // Timestamps
            $table->timestamp('submitted_at')->useCurrent()->comment('When document was submitted');
            $table->timestamp('reviewed_at')->nullable()->comment('When review was completed');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('parent_document_id')
                  ->references('id')
                  ->on('documents')
                  ->onDelete('cascade');
            
            $table->foreign('draft_document_id')
                  ->references('id')
                  ->on('documents')
                  ->onDelete('cascade');
            
            $table->foreign('submitted_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('reviewed_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Indexes for performance
            $table->index('parent_document_id', 'idx_parent_doc');
            $table->index('draft_document_id', 'idx_draft_doc');
            $table->index('status', 'idx_status');
            $table->index('relationship_type', 'idx_rel_type');
            $table->index(['status', 'relationship_type'], 'idx_status_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_relationships');
    }
};
