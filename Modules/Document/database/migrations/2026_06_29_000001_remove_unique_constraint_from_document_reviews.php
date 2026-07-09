<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop unique constraint
        DB::statement('ALTER TABLE document_reviews DROP INDEX document_reviews_document_id_user_id_unique');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down(): void
    {
        // Re-add unique constraint
        DB::statement('ALTER TABLE document_reviews ADD UNIQUE INDEX document_reviews_document_id_user_id_unique (document_id, user_id)');
    }
};
