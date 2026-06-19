<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\RoadmapLesson;

class RoadmapSeeder extends Seeder
{
    public function run(): void
    {
        // Create or get a test user for author
        $author = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
            ]
        );

        // Roadmap 1: Backend Developer
        $backend = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Backend Developer',
            'slug' => 'lo-trinh-backend-developer',
            'description' => 'Học ngôn ngữ PHP, làm chủ framework Laravel và thiết kế cơ sở dữ liệu MySQL.',
            'objective' => 'PHP, Laravel, MySQL, RESTful API',
            'category' => 'backend',
            'level' => 'intermediate',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        // Stage 1: PHP Fundamentals
        $backendStage1 = RoadmapSection::create([
            'roadmap_id' => $backend->id,
            'title' => 'PHP Fundamentals',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $backend->id,
            'section_id' => $backendStage1->id,
            'title' => 'Giới thiệu PHP và cài đặt môi trường',
            'slug' => 'gioi-thieu-php-' . time(),
            'lesson_type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => '<p>Học cách cài đặt PHP và Composer.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $backend->id,
            'section_id' => $backendStage1->id,
            'title' => 'PHP Syntax và Variables',
            'slug' => 'php-syntax-' . (time() + 1),
            'lesson_type' => 'text',
            'content' => '<h3>Biến trong PHP</h3><p>PHP là ngôn ngữ dynamic typing.</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Stage 2: Laravel Framework
        $backendStage2 = RoadmapSection::create([
            'roadmap_id' => $backend->id,
            'title' => 'Laravel Framework',
            'sort_order' => 2,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $backend->id,
            'section_id' => $backendStage2->id,
            'title' => 'Routes và Controllers',
            'slug' => 'routes-controllers-' . (time() + 2),
            'lesson_type' => 'text',
            'content' => '<h3>Routing trong Laravel</h3><p>Định nghĩa routes và controllers.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Roadmap 2: Frontend Developer
        $frontend = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Frontend Developer',
            'slug' => 'lo-trinh-frontend-developer',
            'description' => 'Làm chủ HTML5, CSS3, JavaScript và ReactJS.',
            'objective' => 'HTML, CSS, JavaScript, React',
            'category' => 'frontend',
            'level' => 'beginner',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        // Stage 1: HTML & CSS
        $frontendStage1 = RoadmapSection::create([
            'roadmap_id' => $frontend->id,
            'title' => 'HTML & CSS Basics',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $frontend->id,
            'section_id' => $frontendStage1->id,
            'title' => 'HTML5 Semantic Tags',
            'slug' => 'html5-semantic-' . (time() + 3),
            'lesson_type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => '<p>Tìm hiểu các thẻ HTML5 semantic.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $frontend->id,
            'section_id' => $frontendStage1->id,
            'title' => 'CSS Flexbox và Grid',
            'slug' => 'css-flexbox-grid-' . (time() + 4),
            'lesson_type' => 'text',
            'content' => '<h3>Layout với Flexbox và Grid</h3><p>Hai công cụ mạnh mẽ.</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Stage 2: JavaScript
        $frontendStage2 = RoadmapSection::create([
            'roadmap_id' => $frontend->id,
            'title' => 'JavaScript Fundamentals',
            'sort_order' => 2,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $frontend->id,
            'section_id' => $frontendStage2->id,
            'title' => 'JavaScript Basics và DOM',
            'slug' => 'javascript-dom-' . (time() + 5),
            'lesson_type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => '<p>Học cách manipulate DOM.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $frontend->id,
            'section_id' => $frontendStage2->id,
            'title' => 'ES6+ Modern JavaScript',
            'slug' => 'es6-features-' . (time() + 6),
            'lesson_type' => 'text',
            'content' => '<h3>ES6+</h3><p>Arrow functions, async/await.</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $this->command->info('✅ Seeded: 2 roadmaps, 4 sections, 8 lessons!');
    }
}
