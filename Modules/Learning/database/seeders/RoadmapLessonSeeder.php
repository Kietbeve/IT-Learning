<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\Project;

class RoadmapLessonSeeder extends Seeder
{
    /**
     * Seed sample lessons with different types for testing
     */
    public function run(): void
    {
        // Find or create a test roadmap
        $roadmap = Roadmap::first();
        
        if (!$roadmap) {
            $roadmap = Roadmap::create([
                'title' => 'Lộ trình Frontend Developer',
                'description' => 'Học HTML, CSS, JavaScript và React',
                'category' => 'Frontend',
                'level' => 'beginner',
                'status' => 'published',
            ]);
        }

        // Create sections
        $section1 = RoadmapSection::firstOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'HTML & CSS Cơ bản'],
            ['sort_order' => 1]
        );

        $section2 = RoadmapSection::firstOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'JavaScript Nâng cao'],
            ['sort_order' => 2]
        );

        $section3 = RoadmapSection::firstOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'React Framework'],
            ['sort_order' => 3]
        );

        // Section 1: HTML & CSS - Mixed lesson types
        RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'Giới thiệu HTML5'],
            [
                'section_id' => $section1->id,
                'slug' => Str::slug('Giới thiệu HTML5'),
                'lesson_type' => 'video',
                'video_url' => 'https://www.youtube.com/embed/qz0aGYrrlhU',
                'content' => '<h2>HTML5 là gì?</h2><p>HTML5 là phiên bản mới nhất của HTML, được sử dụng để xây dựng cấu trúc trang web.</p>',
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'Semantic HTML Tags'],
            [
                'section_id' => $section1->id,
                'slug' => Str::slug('Semantic HTML Tags'),
                'lesson_type' => 'text',
                'content' => '<h2>Semantic Tags</h2><p>Các thẻ semantic giúp cấu trúc trang web rõ ràng hơn: header, nav, main, article, section, footer...</p>',
                'is_published' => true,
                'sort_order' => 2,
            ]
        );

        RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'CSS3 Flexbox & Grid'],
            [
                'section_id' => $section1->id,
                'slug' => Str::slug('CSS3 Flexbox Grid'),
                'lesson_type' => 'video',
                'video_url' => 'https://www.youtube.com/embed/JJSoEo8JSnc',
                'content' => '<h2>Layout với Flexbox và Grid</h2><p>Học cách dùng Flexbox và Grid để tạo layout responsive.</p>',
                'is_published' => true,
                'sort_order' => 3,
            ]
        );

        RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'Kiểm tra HTML/CSS'],
            [
                'section_id' => $section1->id,
                'slug' => Str::slug('Kiểm tra HTML CSS'),
                'lesson_type' => 'exam',
                'content' => '<p>Bài kiểm tra kiến thức HTML và CSS cơ bản.</p>',
                'is_published' => true,
                'sort_order' => 4,
            ]
        );

        // Section 2: JavaScript
        RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'JavaScript ES6+ Syntax'],
            [
                'section_id' => $section2->id,
                'slug' => Str::slug('JavaScript ES6 Syntax'),
                'lesson_type' => 'video',
                'video_url' => 'https://www.youtube.com/embed/NCwa_xi0Uuc',
                'content' => '<h2>ES6 Modern JavaScript</h2><p>Arrow functions, destructuring, spread operator, template literals...</p>',
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'DOM Manipulation'],
            [
                'section_id' => $section2->id,
                'slug' => Str::slug('DOM Manipulation'),
                'lesson_type' => 'text',
                'content' => '<h2>Thao tác với DOM</h2><p>querySelector, addEventListener, createElement, innerHTML...</p>',
                'is_published' => true,
                'sort_order' => 2,
            ]
        );

        RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'Async JavaScript'],
            [
                'section_id' => $section2->id,
                'slug' => Str::slug('Async JavaScript'),
                'lesson_type' => 'video',
                'video_url' => 'https://www.youtube.com/embed/PoRJizFvM7s',
                'content' => '<h2>Promise và Async/Await</h2><p>Xử lý bất đồng bộ trong JavaScript.</p>',
                'is_published' => true,
                'sort_order' => 3,
            ]
        );

        // Section 3: React với Project
        RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'React Hooks cơ bản'],
            [
                'section_id' => $section3->id,
                'slug' => Str::slug('React Hooks cơ bản'),
                'lesson_type' => 'video',
                'video_url' => 'https://www.youtube.com/embed/TNhaISOUy6Q',
                'content' => '<h2>useState và useEffect</h2><p>Hai hooks cơ bản nhất trong React.</p>',
                'is_published' => true,
                'sort_order' => 1,
            ]
        );

        // Create Project for final lesson
        $project = Project::firstOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'Dự án Todo App với React'],
            [
                'section_id' => $section3->id,
                'description' => 'Xây dựng ứng dụng Todo App hoàn chỉnh với React Hooks, bao gồm CRUD operations, localStorage, và responsive design.',
                'starter_code_url' => 'https://github.com/example/react-todo-starter',
                'max_resubmissions' => 3,
                'sort_order' => 1,
            ]
        );

        $projectLesson = RoadmapLesson::updateOrCreate(
            ['roadmap_id' => $roadmap->id, 'title' => 'Project: Todo App React'],
            [
                'section_id' => $section3->id,
                'slug' => Str::slug('Project Todo App React'),
                'lesson_type' => 'project',
                'project_id' => $project->id,
                'content' => '<h2>Dự án cuối khóa: Todo App</h2>
                <h3>Yêu cầu chức năng:</h3>
                <ul>
                    <li>✅ Thêm, sửa, xóa todo</li>
                    <li>✅ Đánh dấu hoàn thành</li>
                    <li>✅ Filter: All, Active, Completed</li>
                    <li>✅ Lưu dữ liệu vào localStorage</li>
                    <li>✅ Responsive design</li>
                </ul>
                <h3>Tech Stack:</h3>
                <p>React, Hooks (useState, useEffect, useReducer), CSS3/Tailwind</p>
                <h3>Nộp bài:</h3>
                <p>Fork starter code, hoàn thành theo yêu cầu, push lên GitHub và deploy lên Vercel/Netlify.</p>',
                'is_published' => true,
                'sort_order' => 2,
            ]
        );

        $this->command->info('✅ Seeded roadmap lessons with types: text, video, exam, project');
        $this->command->info("📚 Roadmap ID: {$roadmap->id}");
        $this->command->info("🎥 Video lessons: 4");
        $this->command->info("📝 Text lessons: 2");
        $this->command->info("📋 Exam lessons: 1");
        $this->command->info("🚀 Project lessons: 1");
    }
}
