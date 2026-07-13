<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'test',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            //seeder từ module Auth phải chạy trước để có Admin
            \Modules\Auth\database\seeders\AuthDatabaseSeeder::class,
            
            //seeder ngoài module (chứa tài liệu, danh mục)
            ITLearningDataSeeder::class,

            \Modules\Exam\database\seeders\QuestionSeeder::class,
            \Modules\Exam\database\seeders\ExamTagMapSeeder::class,
            \Modules\Learning\Database\Seeders\LearningDatabaseSeeder::class,

            //seeder orders và dashboard
            // OrderSeeder::class,
            // DashboardSeeder::class,
            //SettingSeeder::class,
        ]);
    }
}
