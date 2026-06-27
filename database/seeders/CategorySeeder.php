<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('categories')->insert([
            [
                'parent_id' => null,
                'name' => 'PHP',
                'slug' => 'php',
                'type' => 'document',
                'is_active' => true,
                'sort_order' => 1,
                'description' => 'Danh mục PHP',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Laravel',
                'slug' => 'laravel',
                'type' => 'document',
                'is_active' => true,
                'sort_order' => 2,
                'description' => 'Danh mục Laravel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Database',
                'slug' => 'database',
                'type' => 'question',
                'is_active' => true,
                'sort_order' => 3,
                'description' => 'Danh mục Database',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'type' => 'document',
                'is_active' => true,
                'sort_order' => 4,
                'description' => 'Danh mục JavaScript',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Python',
                'slug' => 'python',
                'type' => 'document',
                'is_active' => true,
                'sort_order' => 5,
                'description' => 'Danh mục Python',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Java',
                'slug' => 'java',
                'type' => 'document',
                'is_active' => true,
                'sort_order' => 6,
                'description' => 'Danh mục Java',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'parent_id' => null,
                'name' => 'Mạng máy tính',
                'slug' => 'mang-may-tinh',
                'type' => 'document',
                'is_active' => true,
                'sort_order' => 7,
                'description' => 'Danh mục Mạng máy tính',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
