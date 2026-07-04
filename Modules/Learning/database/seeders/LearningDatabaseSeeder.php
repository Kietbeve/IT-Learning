<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;

class LearningDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gọi file RoadmapSeeder thực thi bơm dữ liệu vào hệ thống
        $this->call([
            RoadmapSeeder::class,
            ForumSeeder::class,
        ]);
    }
}