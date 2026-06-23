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
        Schema::table('contributor_applications', function (Blueprint $table) {
            // Bank Information
            $table->string('bank_name')->nullable()->after('cv_url');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
            
            // Personal Information
            $table->string('id_card_number')->nullable()->after('bank_account_name');
            $table->text('address')->nullable()->after('id_card_number');
            
            // Add indexes for performance
            $table->index('id_card_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contributor_applications', function (Blueprint $table) {
            $table->dropIndex(['id_card_number']);
            $table->dropColumn([
                'bank_name',
                'bank_account_number', 
                'bank_account_name',
                'id_card_number',
                'address'
            ]);
        });
    }
};