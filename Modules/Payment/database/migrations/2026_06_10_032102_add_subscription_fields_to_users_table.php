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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('last_login_at')->nullable()->after('contributor_balance');
            $table->string('last_login_IP', 45)->nullable()->after('last_login_at');
            $table->timestamp('vip_expires_at')->nullable()->after('last_login_IP');
            $table->integer('vip_download_quota')->default(0)->after('vip_expires_at');
            
            $table->index('vip_expires_at');
            $table->index('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['vip_expires_at']);
            $table->dropIndex(['last_login_at']);
            
            $table->dropColumn([
                'last_login_at',
                'last_login_IP',
                'vip_expires_at',
                'vip_download_quota',
            ]);
        });
    }
};
