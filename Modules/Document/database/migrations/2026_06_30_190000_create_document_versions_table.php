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
        // 1. Drop document_relationships first because it has foreign keys to documents
        Schema::dropIfExists('document_relationships');

        // 2. Modify documents table: drop parent_document_id and its foreign key constraint
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['parent_document_id']);
            $table->dropColumn('parent_document_id');
        });

        // 3. Create document_versions table
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->integer('version_number');

            $table->string('title');
            $table->text('short_description')->nullable();
            $table->text('description');
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('subject_id');
            $table->string('thumbnail')->nullable();
            $table->string('file_original_path');
            $table->string('file_watermarked_path')->nullable();
            $table->string('preview_file_path')->nullable();
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->string('visibility')->default('public');
            $table->boolean('is_downloadable')->default(true);
            $table->string('watermark_status')->default('pending');
            $table->decimal('price', 15, 2)->default(0.00);

            // Review status
            $table->string('status')->default('pending'); // pending | approved | rejected
            $table->text('rejected_reason')->nullable();
            
            $table->unsignedBigInteger('submitted_by');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('document_id')
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

            $table->unique(['document_id', 'version_number']);
        });

        // 4. Add current_version_id to documents table
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('current_version_id')->nullable()->after('id');
            $table->foreign('current_version_id')
                  ->references('id')
                  ->on('document_versions')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop foreign key and column current_version_id from documents table
        Schema::table('documents', function (Blueprint $table) {
            $table->dropForeign(['current_version_id']);
            $table->dropColumn('current_version_id');
        });

        // 2. Drop document_versions table
        Schema::dropIfExists('document_versions');

        // 3. Re-add parent_document_id and its foreign key constraint to documents table
        Schema::table('documents', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_document_id')->nullable()->after('id');
            $table->foreign('parent_document_id')
                  ->references('id')
                  ->on('documents')
                  ->onDelete('cascade');
        });

        // 4. Re-create document_relationships table (simplified structure for rollback)
        Schema::create('document_relationships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_document_id')->nullable();
            $table->unsignedBigInteger('draft_document_id');
            $table->string('relationship_type');
            $table->string('status')->default('pending');
            $table->text('rejected_reason')->nullable();
            $table->unsignedBigInteger('submitted_by');
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->foreign('parent_document_id')
                  ->references('id')
                  ->on('documents')
                  ->onDelete('cascade');
            
            $table->foreign('draft_document_id')
                  ->references('id')
                  ->on('documents')
                  ->onDelete('cascade');
        });
    }
};
