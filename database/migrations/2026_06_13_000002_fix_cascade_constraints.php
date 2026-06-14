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
        // Fix roadmap_enrollments foreign keys - add cascade constraints
        Schema::table('roadmap_enrollments', function (Blueprint $table) {
            // Drop existing FKs
            $table->dropForeign(['roadmap_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('roadmap_enrollments', function (Blueprint $table) {
            // Recreate with proper cascade
            $table->foreign('roadmap_id')
                  ->references('id')
                  ->on('roadmaps')
                  ->cascadeOnDelete();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
        });

        // Fix lesson_progresses foreign keys - add cascade constraints
        Schema::table('lesson_progresses', function (Blueprint $table) {
            // Drop existing FKs
            $table->dropForeign(['enrollment_id']);
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('lesson_progresses', function (Blueprint $table) {
            // Recreate with proper cascade
            $table->foreign('enrollment_id')
                  ->references('id')
                  ->on('roadmap_enrollments')
                  ->cascadeOnDelete();
            
            $table->foreign('lesson_id')
                  ->references('id')
                  ->on('roadmap_lessons')
                  ->cascadeOnDelete();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->cascadeOnDelete();
        });

        // Fix order_items foreign keys - add restrict to prevent deletion
        Schema::table('order_items', function (Blueprint $table) {
            // Drop existing FKs
            $table->dropForeign(['product_id']);
            $table->dropForeign(['document_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Recreate with restrict (protect products/documents with orders)
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->restrictOnDelete();
            
            $table->foreign('document_id')
                  ->references('id')
                  ->on('documents')
                  ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert roadmap_enrollments FKs to original (without cascade)
        Schema::table('roadmap_enrollments', function (Blueprint $table) {
            $table->dropForeign(['roadmap_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('roadmap_enrollments', function (Blueprint $table) {
            $table->foreign('roadmap_id')->references('id')->on('roadmaps');
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Revert lesson_progresses FKs to original (without cascade)
        Schema::table('lesson_progresses', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropForeign(['lesson_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('lesson_progresses', function (Blueprint $table) {
            $table->foreign('enrollment_id')->references('id')->on('roadmap_enrollments');
            $table->foreign('lesson_id')->references('id')->on('roadmap_lessons');
            $table->foreign('user_id')->references('id')->on('users');
        });

        // Revert order_items FKs to original (without restrict)
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_id']);
            $table->dropForeign(['document_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('document_id')->references('id')->on('documents');
        });
    }
};
