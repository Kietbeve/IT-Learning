<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Fix document_reviews FK
        Schema::table('document_reviews', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('document_reviews', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix document_downloads FK
        Schema::table('document_downloads', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('document_downloads', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix document_comments FK
        Schema::table('document_comments', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('document_comments', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix document_favorites FK
        Schema::table('document_favorites', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('document_favorites', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix document_tag_maps FK
        Schema::table('document_tag_maps', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('document_tag_maps', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix document_reports FK
        Schema::table('document_reports', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('document_reports', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix products FK
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('products', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix document_accesses FK
        Schema::table('document_accesses', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('document_accesses', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix order_items FK to cascade (was restrict)
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });

        // Fix roadmap_lessons FK
        Schema::table('roadmap_lessons', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::table('roadmap_lessons', function (Blueprint $table) {
            $table->foreign('document_id')
                ->references('id')
                ->on('documents')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // Revert all to original (without cascade)
        Schema::table('document_reviews', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('document_downloads', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('document_comments', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('document_favorites', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('document_tag_maps', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('document_reports', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('document_accesses', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
        Schema::table('roadmap_lessons', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }
};