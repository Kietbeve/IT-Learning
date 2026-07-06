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
        Schema::table('projects', function (Blueprint $table) {
            // Add max score for grading
            $table->decimal('max_score', 5, 2)->default(100.00)->after('max_resubmissions');
            
            // Add grading criteria as JSON for flexible rubrics
            $table->json('grading_criteria')->nullable()->after('max_score');
            
            // Add passing score threshold
            $table->decimal('passing_score', 5, 2)->default(60.00)->after('grading_criteria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['max_score', 'grading_criteria', 'passing_score']);
        });
    }
};
