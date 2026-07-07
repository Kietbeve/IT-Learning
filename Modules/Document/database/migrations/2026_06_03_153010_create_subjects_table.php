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
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('cascade')
                ->comment('Môn học thuộc danh mục nào');
            $table->string('name')->comment('Tên môn học: Lập trình Web');
            $table->string('slug')->unique()->comment('URL slug: lap-trinh-web');
            $table->boolean('is_active')->default(true)->comment('Còn dùng không');
            $table->timestamps();

            // Indexes for performance
            $table->index(['category_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
