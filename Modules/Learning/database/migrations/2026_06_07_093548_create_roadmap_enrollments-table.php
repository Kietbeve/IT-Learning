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
        Schema::create('roadmap_enrollments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('roadmap_id')
                ->constrained('roadmaps');

            $table->foreignId('user_id')
                ->constrained('users');

            $table->string('status')
                ->default('learning');

            $table->decimal('progress_percent', 5, 2)
                ->default(0);

            $table->dateTime('started_at');

            $table->dateTime('completed_at')
                ->nullable();

            $table->timestamps();

            $table->index('roadmap_id');
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_enrollments');
    }
};
