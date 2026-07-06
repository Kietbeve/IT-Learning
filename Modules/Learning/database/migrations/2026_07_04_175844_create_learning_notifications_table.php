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
        Schema::create('learning_notifications', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            // Notification type
            $table->enum('type', [
                'assignment_due', 
                'assignment_graded',
                'quiz_available',
                'section_unlocked',
                'certificate_earned',
                'deadline_reminder',
                'course_update'
            ]);
            
            // Related entities
            $table->morphs('notifiable'); // assignment_id, quiz_id, etc.
            
            $table->foreignId('roadmap_id')
                ->nullable()
                ->constrained('roadmaps')
                ->cascadeOnDelete();
            
            // Notification content
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable()->comment('Additional data for notification');
            
            // Action URL
            $table->string('action_url')->nullable();
            $table->string('action_text')->nullable();
            
            // Priority
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            
            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            
            // Scheduling
            $table->timestamp('scheduled_at')->nullable()->comment('Để gửi thông báo theo lịch');
            
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('type');
            $table->index(['user_id', 'is_read']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_notifications');
    }
};
