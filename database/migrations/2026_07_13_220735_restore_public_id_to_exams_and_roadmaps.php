<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('exams', 'public_id')) {
            Schema::table('exams', function (Blueprint $table) {
                $table->string('public_id')->nullable()->after('id');
            });
            
            // Populate data
            DB::table('exams')->get()->each(function ($exam) {
                DB::table('exams')->where('id', $exam->id)->update(['public_id' => (string) Str::uuid()]);
            });
            
            Schema::table('exams', function (Blueprint $table) {
                $table->string('public_id')->nullable(false)->unique()->change();
            });
        }
        
        if (!Schema::hasColumn('roadmaps', 'public_id')) {
            Schema::table('roadmaps', function (Blueprint $table) {
                $table->string('public_id')->nullable()->after('id');
            });
            
            // Populate data
            DB::table('roadmaps')->get()->each(function ($rm) {
                DB::table('roadmaps')->where('id', $rm->id)->update(['public_id' => 'RM-' . strtoupper(Str::random(8))]);
            });
            
            Schema::table('roadmaps', function (Blueprint $table) {
                $table->string('public_id')->nullable(false)->unique()->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exams_and_roadmaps', function (Blueprint $table) {
            //
        });
    }
};
