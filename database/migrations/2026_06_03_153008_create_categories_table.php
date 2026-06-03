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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('categories')
                ->nullOnDelete();

            $table->string('name');

            $table->string('slug')->unique();

            $table->string('type')
                ->default('document');

            $table->boolean('is_active')
                ->default(true);

            $table->integer('sort_order')
                ->default(0);

            $table->text('description')
                ->nullable();

            $table->timestamps();

            $table->softDeletes();

            $table->index('type');
            $table->index('parent_id');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
