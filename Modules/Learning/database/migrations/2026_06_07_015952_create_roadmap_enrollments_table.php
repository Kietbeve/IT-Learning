<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // Lệnh quyền lực: Ép MySQL tắt kiểm tra liên kết khóa ngoại
    Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('roadmap_enrollments');
    Schema::enableForeignKeyConstraints(); // Bật lại ngay sau khi xóa xong

    Schema::create('roadmap_enrollments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('roadmap_id')->constrained('roadmaps')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('status', 50)->default('learning'); 
        $table->decimal('progress_percent', 5, 2)->default(0);
        $table->dateTime('started_at');
        $table->dateTime('completed_at')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('roadmap_enrollments');
    }
};