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
            $table->string('avatar')->nullable()->after('password');
            $table->string('phone')->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('phone');

            $table->string('google_id')->nullable()->unique()->after('bio');

            $table->string('status')
                ->default('active')
                ->index()
                ->after('google_id');

            $table->text('blocked_reason')->nullable()->after('status');
            $table->timestamp('blocked_at')->nullable()->after('blocked_reason');

            $table->unsignedBigInteger('blocked_by')
                ->nullable()
                ->index()
                ->after('blocked_at');

            $table->foreign('blocked_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->decimal('contributor_balance', 15, 2)
                ->default(0)
                ->after('blocked_by');

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['blocked_by']);

            $table->dropColumn([
                'avatar',
                'phone',
                'bio',
                'google_id',
                'status',
                'blocked_reason',
                'blocked_at',
                'blocked_by',
                'contributor_balance',
                'deleted_at',
            ]);
        });
    }
};
