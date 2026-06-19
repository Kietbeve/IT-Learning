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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            
            // Foreign key to users (admin who created the report)
            $table->foreignId('admin_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            
            // Report details
            $table->string('report_type')
                  ->comment('revenue | users | resources | exams | learning');
            $table->date('date_from');
            $table->date('date_to');
            $table->string('file_url')->nullable();
            $table->string('status')
                  ->default('processing')
                  ->comment('processing | completed | failed');
            
            // Timestamp (only created_at, no updated_at)
            $table->timestamp('created_at')->useCurrent();

            // Indexes for query optimization
            $table->index('admin_id');
            $table->index('report_type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
