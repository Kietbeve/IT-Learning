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
        Schema::create('assignment_rubrics', function (Blueprint $table) {
            $table->id();
            
            // Belongs to assignment
            $table->foreignId('assignment_id')
                ->constrained('assignments')
                ->onDelete('cascade');
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('max_points');
            $table->integer('order')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('assignment_id');
            $table->index('order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignment_rubrics');
    }
};
