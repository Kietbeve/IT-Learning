<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'site_name',
                'value' => 'myapp',
                'group' => 'general',
            ],
            [
                'key' => 'commission_rate',
                'value' => '70',
                'group' => 'payment',
            ],
            [
                'key' => 'min_payout_amount',
                'value' => '200000',
                'group' => 'payment',
            ],
            [
                'key' => 'theme_primary_color',
                'value' => '#6366f1',
                'group' => 'theme',
            ],
            [
                'key' => 'smtp_from_email',
                'value' => 'no-reply@myapp.com',
                'group' => 'email',
            ],
        ]);
    }
}
