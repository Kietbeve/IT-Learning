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
        Schema::create('forum_reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->morphs('reactable'); // reactable_id, reactable_type
            $table->string('reaction_type', 20)->default('like'); // like, love, haha, wow, sad, angry
            $table->timestamps();

            // Một user chỉ có thể react 1 lần cho mỗi item
            $table->unique(['user_id', 'reactable_id', 'reactable_type']);
            
            $table->index(['reactable_id', 'reactable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_reactions');
    }
};
