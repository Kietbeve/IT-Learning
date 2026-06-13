<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::disableForeignKeyConstraints();
    Schema::dropIfExists('lesson_progresses');
    Schema::enableForeignKeyConstraints();

    Schema::create('lesson_progresses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('enrollment_id')->constrained('roadmap_enrollments')->onDelete('cascade');
        $table->foreignId('lesson_id')->constrained('roadmap_lessons')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('status', 50)->default('not_started'); 
        $table->dateTime('started_at')->nullable();
        $table->dateTime('completed_at')->nullable();
        $table->timestamps();
    });
}
    

    public function down(): void
    {
        Schema::dropIfExists('lesson_progresses');
    }
};