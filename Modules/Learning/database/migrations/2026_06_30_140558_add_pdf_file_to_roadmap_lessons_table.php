<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roadmap_lessons', function (Blueprint $table) {
            // Thêm cột lưu tên file PDF (cho phép null nếu bài đó không có PDF)
            $table->string('pdf_file')->nullable()->after('title'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roadmap_lessons', function (Blueprint $table) {
            // Xóa cột nếu rollback
            $table->dropColumn('pdf_file');
        });
    }
};