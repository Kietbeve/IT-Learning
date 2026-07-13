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
        Schema::table('roadmap_sections', function (Blueprint $table) {
            // Thêm mô tả và mục tiêu cho chapter
            $table->text('description')->nullable()->after('title');
            $table->text('objectives')->nullable()->comment('Mục tiêu học tập của chương (JSON array)');
            
            // Điều kiện mở khóa
            $table->foreignId('prerequisite_section_id')
                ->nullable()
                ->constrained('roadmap_sections')
                ->nullOnDelete()
                ->comment('Chương cần hoàn thành trước');
            
            $table->boolean('is_locked')->default(false)->comment('Chương bị khóa cho đến khi hoàn thành prerequisite');
            
            // Thông tin thời gian
            $table->integer('estimated_hours')->nullable()->comment('Thời gian ước tính để hoàn thành (giờ)');
            
            // Điều kiện hoàn thành
            $table->integer('min_completion_percent')->default(80)->comment('% tối thiểu để coi như hoàn thành chương');
            
            // Indexes
            $table->index('prerequisite_section_id');
            $table->index('is_locked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roadmap_sections', function (Blueprint $table) {
            $table->dropForeign(['prerequisite_section_id']);
            $table->dropColumn([
                'description',
                'objectives',
                'prerequisite_section_id',
                'is_locked',
                'estimated_hours',
                'min_completion_percent'
            ]);
        });
    }
};
