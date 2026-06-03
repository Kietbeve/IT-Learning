<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('tags')->insert([
            [
                'name' => 'OOP',
                'slug' => 'oop',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MVC',
                'slug' => 'mvc',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Authentication',
                'slug' => 'authentication',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REST API',
                'slug' => 'rest-api',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
