<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tag;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    public function run()
    {
        $tags = [
            // Programming Languages
            'PHP',
            'Python',
            'JavaScript',
            'Java',
            'C++',
            'C#',
            'C',
            'Ruby',
            'Go',
            'Rust',
            'TypeScript',
            'Swift',
            'Kotlin',
            'Dart',
            
            // Web Development
            'HTML',
            'CSS',
            'React',
            'Vue.js',
            'Angular',
            'Node.js',
            'Laravel',
            'Django',
            'Express',
            'Tailwind CSS',
            'Bootstrap',
            'jQuery',
            'Next.js',
            'Nuxt.js',
            
            // Mobile Development
            'Android',
            'iOS',
            'React Native',
            'Flutter',
            'Xamarin',
            
            // Databases
            'MySQL',
            'PostgreSQL',
            'MongoDB',
            'Redis',
            'SQL',
            'SQLite',
            'Oracle',
            'SQL Server',
            
            // DevOps & Tools
            'Docker',
            'Kubernetes',
            'Git',
            'CI/CD',
            'Linux',
            'AWS',
            'Azure',
            'Google Cloud',
            'Jenkins',
            'Nginx',
            
            // Concepts & Practices
            'OOP',
            'Data Structures',
            'Algorithms',
            'Design Patterns',
            'Testing',
            'Security',
            'Clean Code',
            'SOLID',
            'TDD',
            'Agile',
            
            // AI & Data Science
            'Machine Learning',
            'Deep Learning',
            'AI',
            'Data Science',
            'Neural Networks',
            'TensorFlow',
            'PyTorch',
            'Data Analysis',
            
            // Architecture & Backend
            'API',
            'REST',
            'GraphQL',
            'Microservices',
            'Monolithic',
            'Serverless',
            'Backend',
            'Frontend',
            'Full Stack',
            
            // Vietnamese specific
            'Đại học',
            'Cao đẳng',
            'THPT',
            'Lập trình',
            'Giáo trình',
        ];

        foreach ($tags as $tagName) {
            Tag::updateOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
        }
        
        $this->command->info('✅ Đã seed ' . count($tags) . ' tags');
    }
}
