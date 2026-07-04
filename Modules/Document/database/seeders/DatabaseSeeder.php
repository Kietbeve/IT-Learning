<?php

namespace Modules\Document\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the module database seeds.
     */
    public function run(): void
    {
        $this->call([
            SubjectSeeder::class,
        ]);
    }
}
