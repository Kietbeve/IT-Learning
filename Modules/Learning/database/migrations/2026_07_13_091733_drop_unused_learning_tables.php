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
        // 1. Remove foreign key and column from assignment_feedback
        if (Schema::hasColumn('assignment_feedback', 'rubric_item_id')) {
            Schema::table('assignment_feedback', function (Blueprint $table) {
                // Tên khóa ngoại tự động tạo thường là: bảng_cột_foreign
                $table->dropForeign(['rubric_item_id']);
                $table->dropColumn('rubric_item_id');
            });
        }

        // 2. Disable foreign key checks temporarily (SQLite/MySQL compatible) to drop safely
        Schema::disableForeignKeyConstraints();

        // Drop the tables
        Schema::dropIfExists('lesson_practice_answers');
        Schema::dropIfExists('lesson_practice_questions');
        Schema::dropIfExists('lesson_practice_progress');
        Schema::dropIfExists('assignment_graders');
        Schema::dropIfExists('assignment_rubric_items');
        Schema::dropIfExists('assignment_rubrics');
        Schema::dropIfExists('roadmap_notes');
        Schema::dropIfExists('roadmap_questions');
        Schema::dropIfExists('lesson_progresses');

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Phục hồi lại thì rất phức tạp và các bảng này oh vốn dĩ đã bị xóa do không sử dụng.
        // Không định nghĩa hàm down() cho việc drop unused tables.
    }
};
