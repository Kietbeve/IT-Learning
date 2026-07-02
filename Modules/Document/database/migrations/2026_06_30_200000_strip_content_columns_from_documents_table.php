<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Strip all content columns from `documents` table.
 * After this migration, `documents` only holds identity/pointer data.
 * All content (title, files, visibility, etc.) lives in `document_versions`.
 *
 * Target structure of `documents`:
 *   id, public_id, author_id, slug, status,
 *   current_version_id,
 *   download_count, favorite_count, view_count,
 *   created_at, updated_at, deleted_at
 */
return new class extends Migration
{
    /**
     * Columns to remove from `documents` table.
     * They are now exclusively stored in `document_versions`.
     */
    private array $contentColumns = [
        'category_id',
        'subject_id',
        'title',
        'short_description',
        'description',
        'thumbnail',
        'preview_file_path',
        'file_original_path',
        'file_watermarked_path',
        'file_type',
        'file_size',
        'visibility',
        'is_downloadable',
        'watermark_status',
        'rejected_reason',
        'reviewed_by',
        'reviewed_at',
        'published_at',
    ];

    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // 1. Drop foreign keys that reference columns we're about to delete
            $foreignsToDrop = [];

            if (Schema::hasColumn('documents', 'category_id')) {
                $foreignsToDrop[] = 'category_id';
            }
            if (Schema::hasColumn('documents', 'subject_id')) {
                $foreignsToDrop[] = 'subject_id';
            }
            if (Schema::hasColumn('documents', 'reviewed_by')) {
                $foreignsToDrop[] = 'reviewed_by';
            }

            foreach ($foreignsToDrop as $col) {
                try {
                    $table->dropForeign([$col]);
                } catch (\Exception $e) {
                    // FK may already be gone — safe to ignore
                }
            }

            // 2. Drop the content columns
            $columnsToDrop = array_filter(
                $this->contentColumns,
                fn($col) => Schema::hasColumn('documents', $col)
            );

            if (!empty($columnsToDrop)) {
                $table->dropColumn(array_values($columnsToDrop));
            }
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Restore content columns (approximate types for rollback)
            $table->unsignedBigInteger('category_id')->nullable()->after('author_id');
            $table->unsignedBigInteger('subject_id')->nullable()->after('category_id');
            $table->string('title')->after('subject_id');
            $table->text('short_description')->nullable()->after('title');
            $table->longText('description')->after('short_description');
            $table->string('thumbnail')->nullable()->after('description');
            $table->string('preview_file_path')->nullable()->after('thumbnail');
            $table->string('file_original_path')->after('preview_file_path');
            $table->string('file_watermarked_path')->nullable()->after('file_original_path');
            $table->string('file_type')->after('file_watermarked_path');
            $table->bigInteger('file_size')->after('file_type');
            $table->string('visibility')->default('public')->after('file_size');
            $table->boolean('is_downloadable')->default(true)->after('visibility');
            $table->string('watermark_status')->default('pending')->after('is_downloadable');
            $table->text('rejected_reason')->nullable()->after('status');
            $table->unsignedBigInteger('reviewed_by')->nullable()->after('rejected_reason');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->timestamp('published_at')->nullable()->after('reviewed_at');

            // Restore foreign keys
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('set null');
            $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
