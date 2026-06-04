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
        Schema::create('roadmaps', function (Blueprint $table) {
        $table->id();

        $table->string('public_id')->unique();

        $table->foreignId('author_id')
            ->constrained('users')
            ->cascadeOnDelete();

        $table->foreignId('category_id')
            ->nullable()
            ->constrained('categories')
            ->nullOnDelete();

        $table->string('title');

        $table->string('slug')->unique();

        $table->text('short_description')
            ->nullable();

        $table->longText('description');

        $table->longText('objective')
            ->nullable();

        $table->string('thumbnail')
            ->nullable();

        $table->enum('visibility', [
            'public',
            'private',
            'unlisted',
        ])->default('public');

        $table->enum('status', [
            'draft',
            'pending',
            'approved',
            'rejected',
        ])->default('pending');

        $table->longText('rejected_reason')
            ->nullable();

        $table->foreignId('reviewed_by')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

        $table->timestamp('reviewed_at')
            ->nullable();

        $table->timestamp('published_at')
            ->nullable();

        $table->timestamps();

        $table->softDeletes();

        $table->index('author_id');
        $table->index('category_id');
        $table->index('status');
        $table->index('visibility');
        $table->index('reviewed_by');
        $table->index('published_at');

        $table->index([
            'status',
            'published_at',
        ]);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmaps');
    }
};
