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
            //seeder ngoài module
            CategorySeeder::class,
            TagSeeder::class,
            SettingSeeder::class,
            //seeder từ module
            \Modules\Auth\database\seeders\AuthDatabaseSeeder::class,
            \Modules\Exam\database\seeders\QuestionSeeder::class,
            \Modules\Exam\database\seeders\ExamTagMapSeeder::class,
            \Modules\Document\database\seeders\DocumentDatabaseSeeder::class,
            //seeder orders và dashboard
            OrderSeeder::class,
            DashboardSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
