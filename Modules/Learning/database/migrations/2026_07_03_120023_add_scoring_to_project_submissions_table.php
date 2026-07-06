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
        Schema::table('project_submissions', function (Blueprint $table) {
            // Add scoring system
            $table->decimal('score', 5, 2)->nullable()->after('feedback');
            $table->json('grading_notes')->nullable()->after('score');
            
            // Add submission type to clarify how project was submitted
            $table->enum('submission_type', ['file', 'demo', 'both'])->default('file')->after('note');
            
            // Add index for better query performance
            $table->index('score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->dropColumn(['score', 'grading_notes', 'submission_type']);
            $table->dropIndex(['score']);
        });
    }
};
