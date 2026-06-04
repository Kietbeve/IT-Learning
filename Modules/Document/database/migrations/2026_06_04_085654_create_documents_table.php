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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('public_id')->unique(); // ULID for public URL

            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description');

            $table->string('thumbnail')->nullable();
            $table->string('preview_file_path')->nullable();
            $table->string('file_original_path');
            $table->string('file_watermarked_path')->nullable();

            $table->string('file_type'); // pdf | docx | zip
            $table->bigInteger('file_size');

            $table->string('visibility')->default('public'); // public | private | unlisted
            $table->boolean('is_downloadable')->default(true);

            $table->string('watermark_status')->default('pending'); // pending | processing | success | failed
            $table->string('status')->default('pending'); // draft | pending | approved | rejected | unpublished
            $table->text('rejected_reason')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();

            $table->integer('download_count')->default(0);
            $table->integer('favorite_count')->default(0);
            $table->integer('view_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes chuẩn xác từ DBML
            $table->index('public_id');
            $table->index('slug');
            $table->index('author_id');
            $table->index('category_id');
            $table->index('status');
            $table->index('visibility');
            $table->index('watermark_status');
            $table->index('reviewed_by');
            $table->index('published_at');
            $table->index('created_at');
            $table->index(['status', 'published_at']);
            $table->index(['author_id', 'status']);
            $table->index(['category_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
