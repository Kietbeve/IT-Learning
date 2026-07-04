<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        if (!Schema::hasColumn('document_reviews', 'document_download_id')) {
            Schema::table('document_reviews', function (Blueprint $table) {
                $table->unsignedBigInteger('document_download_id')
                      ->nullable()
                      ->after('user_id');
                
                $table->foreign('document_download_id')
                      ->references('id')
                      ->on('document_downloads')
                      ->onDelete('set null');
                
                $table->index('document_download_id');
            });
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        if (Schema::hasColumn('document_reviews', 'document_download_id')) {
            Schema::table('document_reviews', function (Blueprint $table) {
                $table->dropForeign(['document_download_id']);
                $table->dropIndex(['document_download_id']);
                $table->dropColumn('document_download_id');
            });
        }
    }
};
