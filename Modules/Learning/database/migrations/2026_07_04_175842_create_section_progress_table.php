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
        Schema::create('section_progress', function (Blueprint $table) {
            $table->id();
            
            // User và Section tracking
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->foreignId('section_id')
                ->constrained('roadmap_sections')
                ->cascadeOnDelete();
            
            $table->foreignId('roadmap_id')
                ->constrained('roadmaps')
                ->cascadeOnDelete();
            
            // Tiến độ hoàn thành
            $table->integer('completed_lessons')->default(0);
            $table->integer('total_lessons')->default(0);
            $table->decimal('completion_percent', 5, 2)->default(0);
            
            // Trạng thái
            $table->enum('status', ['not_started', 'in_progress', 'completed', 'locked'])->default('not_started');
            
            // Thời gian
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('time_spent_minutes')->default(0)->comment('Tổng thời gian học (phút)');
            
            $table->timestamps();
            
            // Indexes
            $table->unique(['user_id', 'section_id']);
            $table->index('roadmap_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_progress');
    }
};
