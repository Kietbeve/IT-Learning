<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Bảng phân công reviewer (Admin/CTV) chấm project.
     * Một project có thể có nhiều reviewer (để phân tải công việc).
     */
    public function up(): void
    {
        Schema::create('project_reviewers', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete()
                ->comment('Project được phân công');
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->comment('Reviewer (Admin hoặc CTV được phân công)');
            
            // Assignment metadata
            $table->foreignId('assigned_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->comment('Người phân công (thường là Admin)');
            
            $table->timestamp('assigned_at')->useCurrent()->comment('Thời điểm phân công');
            
            // Reviewer settings
            $table->boolean('is_active')->default(true)->comment('Reviewer này còn active không');
            $table->boolean('can_final_grade')->default(false)->comment('Có quyền chấm điểm cuối cùng không');
            
            // Statistics
            $table->integer('reviews_count')->default(0)->comment('Số lượng submission đã review');
            $table->timestamp('last_review_at')->nullable()->comment('Lần review gần nhất');
            
            $table->timestamps();
            
            // Unique: một user chỉ được assign 1 lần cho 1 project
            $table->unique(['project_id', 'user_id'], 'unique_project_reviewer');
            
            // Indexes
            $table->index('user_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_reviewers');
    }
};
