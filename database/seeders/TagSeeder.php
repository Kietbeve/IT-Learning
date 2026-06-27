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
                    [
                'name' => 'Laravel',
                'slug' => 'laravel',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PHP',
                'slug' => 'php',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'JavaScript',
                'slug' => 'javascript',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'React',
                'slug' => 'react',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VueJS',
                'slug' => 'vuejs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'NodeJS',
                'slug' => 'nodejs',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'HTML',
                'slug' => 'html',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CSS',
                'slug' => 'css',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'TailwindCSS',
                'slug' => 'tailwindcss',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bootstrap',
                'slug' => 'bootstrap',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MySQL',
                'slug' => 'mysql',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PostgreSQL',
                'slug' => 'postgresql',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MongoDB',
                'slug' => 'mongodb',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'API',
                'slug' => 'api',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'REST',
                'slug' => 'rest',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GraphQL',
                'slug' => 'graphql',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Docker',
                'slug' => 'docker',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kubernetes',
                'slug' => 'kubernetes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DevOps',
                'slug' => 'devops',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Python',
                'slug' => 'python',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Django',
                'slug' => 'django',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Flask',
                'slug' => 'flask',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ruby',
                'slug' => 'ruby',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rails',
                'slug' => 'rails',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GoLang',
                'slug' => 'golang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Swift',
                'slug' => 'swift',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kotlin',
                'slug' => 'kotlin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CSharp',
                'slug' => 'csharp',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rust',
                'slug' => 'rust',
                'created_at' => now(),
                'updated_at' => now(),
            ],
                ]);
    }
}       
