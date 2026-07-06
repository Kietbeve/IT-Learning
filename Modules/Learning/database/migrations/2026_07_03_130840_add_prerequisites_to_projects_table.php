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
            // Prerequisite: % of lessons in section must be completed before submission
            $table->decimal('required_completion_percentage', 5, 2)
                ->default(80.00)
                ->after('passing_score')
                ->comment('Percentage of section lessons that must be completed (0-100)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('required_completion_percentage');
        });
    }
};
