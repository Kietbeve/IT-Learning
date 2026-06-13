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
        // Add missing foreign key: document_downloads.order_item_id -> order_items.id
        Schema::table('document_downloads', function (Blueprint $table) {
            $table->foreign('order_item_id')
                  ->references('id')
                  ->on('order_items')
                  ->nullOnDelete();
        });

        // Add missing foreign key: project_submissions.enrollment_id -> roadmap_enrollments.id
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->foreign('enrollment_id')
                  ->references('id')
                  ->on('roadmap_enrollments')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_downloads', function (Blueprint $table) {
            $table->dropForeign(['order_item_id']);
        });

        Schema::table('project_submissions', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
        });
    }
};
