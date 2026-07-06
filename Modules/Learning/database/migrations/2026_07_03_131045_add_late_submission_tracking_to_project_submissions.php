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
            // Track if submission is late
            $table->boolean('is_late')
                ->default(false)
                ->after('submitted_at')
                ->comment('Whether submission was made after deadline');
            
            // Track how many days late
            $table->integer('days_late')
                ->default(0)
                ->after('is_late')
                ->comment('Number of days after deadline');
            
            // Index for filtering late submissions
            $table->index('is_late');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_submissions', function (Blueprint $table) {
            $table->dropIndex(['is_late']);
            $table->dropColumn(['is_late', 'days_late']);
        });
    }
};
