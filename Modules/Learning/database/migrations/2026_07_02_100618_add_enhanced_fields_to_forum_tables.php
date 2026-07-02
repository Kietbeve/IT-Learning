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
        // Add fields to forum_posts
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->boolean('is_pinned')->default(false)->after('is_best_answer'); // Pin comment
            $table->timestamp('edited_at')->nullable()->after('updated_at'); // Thời điểm chỉnh sửa
            $table->text('mentions')->nullable()->after('content'); // JSON array of mentioned user IDs
            $table->string('share_token', 32)->unique()->nullable()->after('mentions'); // Token để share link
        });

        // Add fields to forum_threads
        Schema::table('forum_threads', function (Blueprint $table) {
            $table->string('share_token', 32)->unique()->nullable()->after('view_count'); // Token để share link
            $table->enum('comment_sort', ['latest', 'oldest', 'popular'])->default('latest')->after('share_token'); // Sort preference
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropColumn(['is_pinned', 'edited_at', 'mentions', 'share_token']);
        });

        Schema::table('forum_threads', function (Blueprint $table) {
            $table->dropColumn(['share_token', 'comment_sort']);
        });
    }
};
