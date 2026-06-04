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
        Schema::create('project_submissions', function (Blueprint $table) {
        $table->id();

        $table->foreignId('project_id')
            ->constrained('projects')
            ->cascadeOnDelete();

        $table->foreignId('user_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->unsignedBigInteger('enrollment_id')
            ->nullable();

        $table->string('github_url')
            ->nullable();

        $table->string('live_demo_url')
            ->nullable();

        $table->string('attachment_path')
            ->nullable();

        $table->longText('note')
            ->nullable();

        $table->unsignedTinyInteger('submission_no')
            ->default(1);

        $table->enum('status', [
            'submitted',
            'in_review',
            'passed',
            'failed',
            'resubmitted',
        ])->default('submitted');

        $table->foreignId('reviewed_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('reviewed_at')
            ->nullable();

        $table->longText('feedback')
            ->nullable();

        $table->timestamp('submitted_at');

        $table->timestamps();

        $table->index('project_id');
        $table->index('user_id');
        $table->index('status');
        $table->index('reviewed_by');
        $table->index('submitted_at');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_submissions');
    }
};
