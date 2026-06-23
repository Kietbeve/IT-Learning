<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\Project;

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
            'video_url' => 'https://youtu.be/0Zay4yjYxJc?si=hldX_GIgAG2g6_Df',
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

        // Project for Stage 1
        $project1 = Project::create([
            'roadmap_id' => $backend->id,
            'section_id' => $backendStage1->id,
            'title' => 'Project: PHP Fundamentals Practice',
            'description' => 'Xây dựng ứng dụng PHP đơn giản để thực hành các kiến thức cơ bản',
            'starter_code_url' => 'https://github.com/example/php-fundamentals-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $backend->id,
            'section_id' => $backendStage1->id,
            'title' => 'Project: PHP Fundamentals Practice',
            'slug' => 'project-php-fundamentals-' . (time() + 100),
            'lesson_type' => 'project',
            'project_id' => $project1->id,
            'content' => '<p>Hoàn thành project thực hành PHP Fundamentals</p>',
            'is_published' => true,
            'sort_order' => 3,
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

        // Project for Stage 2
        $project2 = Project::create([
            'roadmap_id' => $backend->id,
            'section_id' => $backendStage2->id,
            'title' => 'Project: Laravel CRUD Application',
            'description' => 'Xây dựng ứng dụng CRUD với Laravel framework',
            'starter_code_url' => 'https://github.com/example/laravel-crud-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $backend->id,
            'section_id' => $backendStage2->id,
            'title' => 'Project: Laravel CRUD Application',
            'slug' => 'project-laravel-crud-' . (time() + 101),
            'lesson_type' => 'project',
            'project_id' => $project2->id,
            'content' => '<p>Hoàn thành project Laravel CRUD Application</p>',
            'is_published' => true,
            'sort_order' => 2,
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
            'video_url' => 'https://youtu.be/kX3TfdUqpuU?si=KKlzRQAgFKazulzT',
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

        // Project for Frontend Stage 1
        $project3 = Project::create([
            'roadmap_id' => $frontend->id,
            'section_id' => $frontendStage1->id,
            'title' => 'Project: Responsive Landing Page',
            'description' => 'Xây dựng landing page responsive với HTML5 và CSS3',
            'starter_code_url' => 'https://github.com/example/landing-page-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $frontend->id,
            'section_id' => $frontendStage1->id,
            'title' => 'Project: Responsive Landing Page',
            'slug' => 'project-landing-page-' . (time() + 102),
            'lesson_type' => 'project',
            'project_id' => $project3->id,
            'content' => '<p>Hoàn thành project Landing Page</p>',
            'is_published' => true,
            'sort_order' => 3,
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
            'video_url' => 'https://youtu.be/lkIFF4maKMU?si=s2aVGgJUm9IbvlDd',
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

        // Project for Frontend Stage 2
        $project4 = Project::create([
            'roadmap_id' => $frontend->id,
            'section_id' => $frontendStage2->id,
            'title' => 'Project: Interactive JavaScript App',
            'description' => 'Xây dựng ứng dụng JavaScript tương tác với DOM và ES6+',
            'starter_code_url' => 'https://github.com/example/js-app-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $frontend->id,
            'section_id' => $frontendStage2->id,
            'title' => 'Project: Interactive JavaScript App',
            'slug' => 'project-js-app-' . (time() + 103),
            'lesson_type' => 'project',
            'project_id' => $project4->id,
            'content' => '<p>Hoàn thành project JavaScript App</p>',
            'is_published' => true,
            'sort_order' => 3,
        ]);

        // Roadmap 3: Fullstack Developer
        $fullstack = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Fullstack Developer',
            'slug' => 'lo-trinh-fullstack-developer',
            'description' => 'Kết hợp kỹ năng Frontend và Backend để trở thành Fullstack Developer toàn diện.',
            'objective' => 'HTML, CSS, JavaScript, React, Node.js, Database',
            'category' => 'fullstack',
            'level' => 'advanced',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $fullstackStage1 = RoadmapSection::create([
            'roadmap_id' => $fullstack->id,
            'title' => 'Fullstack Foundation',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $fullstack->id,
            'section_id' => $fullstackStage1->id,
            'title' => 'Kiến trúc hệ thống Fullstack',
            'slug' => 'kien-truc-fullstack-' . (time() + 7),
            'lesson_type' => 'text',
            'content' => '<p>Tìm hiểu kiến trúc tổng thể của ứng dụng Fullstack.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Project for Fullstack Stage 1
        $project5 = Project::create([
            'roadmap_id' => $fullstack->id,
            'section_id' => $fullstackStage1->id,
            'title' => 'Project: Fullstack Web Application',
            'description' => 'Xây dựng ứng dụng web fullstack với frontend và backend',
            'starter_code_url' => 'https://github.com/example/fullstack-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $fullstack->id,
            'section_id' => $fullstackStage1->id,
            'title' => 'Project: Fullstack Web Application',
            'slug' => 'project-fullstack-app-' . (time() + 104),
            'lesson_type' => 'project',
            'project_id' => $project5->id,
            'content' => '<p>Hoàn thành project Fullstack Web Application</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Roadmap 4: Mobile Development
        $mobile = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Mobile Development',
            'slug' => 'lo-trinh-mobile-development',
            'description' => 'Xây dựng ứng dụng di động đa nền tảng với Flutter hoặc React Native.',
            'objective' => 'Flutter, React Native, Mobile UI/UX',
            'category' => 'mobile',
            'level' => 'intermediate',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $mobileStage1 = RoadmapSection::create([
            'roadmap_id' => $mobile->id,
            'title' => 'Mobile Basics',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $mobile->id,
            'section_id' => $mobileStage1->id,
            'title' => 'Giới thiệu Flutter/React Native',
            'slug' => 'mobile-intro-' . (time() + 8),
            'lesson_type' => 'video',
            'video_url' => 'https://youtu.be/-Uk9qH39S94?si=wMMZpZ49TeyN_CRw',
            'content' => '<p>Lựa chọn framework phù hợp cho Mobile Development.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Project for Mobile Stage 1
        $project6 = Project::create([
            'roadmap_id' => $mobile->id,
            'section_id' => $mobileStage1->id,
            'title' => 'Project: Mobile App Prototype',
            'description' => 'Xây dựng prototype ứng dụng mobile đơn giản',
            'starter_code_url' => 'https://github.com/example/mobile-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $mobile->id,
            'section_id' => $mobileStage1->id,
            'title' => 'Project: Mobile App Prototype',
            'slug' => 'project-mobile-app-' . (time() + 105),
            'lesson_type' => 'project',
            'project_id' => $project6->id,
            'content' => '<p>Hoàn thành project Mobile App Prototype</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Roadmap 5: DevOps Engineer
        $devops = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình DevOps Engineer',
            'slug' => 'lo-trinh-devops-engineer',
            'description' => 'Làm chủ CI/CD, Docker, Kubernetes và quản lý hạ tầng Cloud.',
            'objective' => 'Docker, Kubernetes, CI/CD, AWS, Linux',
            'category' => 'devops',
            'level' => 'advanced',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $devopsStage1 = RoadmapSection::create([
            'roadmap_id' => $devops->id,
            'title' => 'DevOps Fundamentals',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $devops->id,
            'section_id' => $devopsStage1->id,
            'title' => 'Linux và Shell Scripting',
            'slug' => 'linux-shell-' . (time() + 9),
            'lesson_type' => 'text',
            'content' => '<p>Nền tảng Linux cho DevOps Engineers.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $devops->id,
            'section_id' => $devopsStage1->id,
            'title' => 'Docker Container cơ bản',
            'slug' => 'docker-basics-' . (time() + 10),
            'lesson_type' => 'video',
            'video_url' => 'https://youtu.be/DQdB7wFEygo?si=Y3bDPNIjMbkUJju-',
            'content' => '<p>Containerize ứng dụng với Docker.</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Project for DevOps Stage 1
        $project7 = Project::create([
            'roadmap_id' => $devops->id,
            'section_id' => $devopsStage1->id,
            'title' => 'Project: Dockerize Application',
            'description' => 'Containerize một ứng dụng web với Docker',
            'starter_code_url' => 'https://github.com/example/docker-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $devops->id,
            'section_id' => $devopsStage1->id,
            'title' => 'Project: Dockerize Application',
            'slug' => 'project-dockerize-app-' . (time() + 106),
            'lesson_type' => 'project',
            'project_id' => $project7->id,
            'content' => '<p>Hoàn thành project Dockerize Application</p>',
            'is_published' => true,
            'sort_order' => 3,
        ]);

        // Roadmap 6: Data Science & AI
        $dataScience = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Data Science & AI',
            'slug' => 'lo-trinh-data-science-ai',
            'description' => 'Phân tích dữ liệu, xây dựng mô hình Machine Learning và Deep Learning với Python.',
            'objective' => 'Python, Pandas, NumPy, Scikit-learn, TensorFlow',
            'category' => 'data-science',
            'level' => 'advanced',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $dsStage1 = RoadmapSection::create([
            'roadmap_id' => $dataScience->id,
            'title' => 'Python for Data Science',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $dataScience->id,
            'section_id' => $dsStage1->id,
            'title' => 'Python cơ bản cho Data Science',
            'slug' => 'python-data-science-' . (time() + 11),
            'lesson_type' => 'video',
            'video_url' => 'https://youtu.be/HrRA67O-QXI?si=uxIuwUh_6fdLipMA',
            'content' => '<p>Làm quen với Python, Pandas và NumPy.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Project for Data Science Stage 1
        $project8 = Project::create([
            'roadmap_id' => $dataScience->id,
            'section_id' => $dsStage1->id,
            'title' => 'Project: Data Analysis with Python',
            'description' => 'Phân tích dữ liệu với Python, Pandas và NumPy',
            'starter_code_url' => 'https://github.com/example/data-analysis-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $dataScience->id,
            'section_id' => $dsStage1->id,
            'title' => 'Project: Data Analysis with Python',
            'slug' => 'project-data-analysis-' . (time() + 107),
            'lesson_type' => 'project',
            'project_id' => $project8->id,
            'content' => '<p>Hoàn thành project Data Analysis</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Roadmap 7: Cybersecurity
        $security = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Cybersecurity',
            'slug' => 'lo-trinh-cybersecurity',
            'description' => 'Bảo mật ứng dụng, phát hiện lỗ hổng và phòng chống tấn công mạng.',
            'objective' => 'Network Security, Penetration Testing, Ethical Hacking',
            'category' => 'security',
            'level' => 'expert',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $securityStage1 = RoadmapSection::create([
            'roadmap_id' => $security->id,
            'title' => 'Security Fundamentals',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $security->id,
            'section_id' => $securityStage1->id,
            'title' => 'Cơ bản về an ninh mạng',
            'slug' => 'network-security-' . (time() + 12),
            'lesson_type' => 'text',
            'content' => '<p>Tìm hiểu các nguyên tắc bảo mật cơ bản.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Project for Security Stage 1
        $project9 = Project::create([
            'roadmap_id' => $security->id,
            'section_id' => $securityStage1->id,
            'title' => 'Project: Security Audit',
            'description' => 'Thực hiện audit bảo mật cơ bản cho một ứng dụng web',
            'starter_code_url' => 'https://github.com/example/security-audit-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $security->id,
            'section_id' => $securityStage1->id,
            'title' => 'Project: Security Audit',
            'slug' => 'project-security-audit-' . (time() + 108),
            'lesson_type' => 'project',
            'project_id' => $project9->id,
            'content' => '<p>Hoàn thành project Security Audit</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Roadmap 8: Cloud Computing
        $cloud = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Cloud Computing',
            'slug' => 'lo-trinh-cloud-computing',
            'description' => 'Quản lý hạ tầng đám mây với AWS, Azure và Google Cloud Platform.',
            'objective' => 'AWS, Azure, GCP, Cloud Architecture',
            'category' => 'cloud',
            'level' => 'intermediate',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $cloudStage1 = RoadmapSection::create([
            'roadmap_id' => $cloud->id,
            'title' => 'Cloud Basics',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $cloud->id,
            'section_id' => $cloudStage1->id,
            'title' => 'Giới thiệu Cloud Computing',
            'slug' => 'cloud-intro-' . (time() + 13),
            'lesson_type' => 'video',
            'video_url' => 'https://youtu.be/ZaA0kNm18pE?si=31s_JM9KjPNIhln_',
            'content' => '<p>Các dịch vụ Cloud phổ biến: AWS, Azure, GCP.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Project for Cloud Stage 1
        $project10 = Project::create([
            'roadmap_id' => $cloud->id,
            'section_id' => $cloudStage1->id,
            'title' => 'Project: Deploy to Cloud',
            'description' => 'Deploy ứng dụng lên AWS/Azure/GCP',
            'starter_code_url' => 'https://github.com/example/cloud-deploy-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $cloud->id,
            'section_id' => $cloudStage1->id,
            'title' => 'Project: Deploy to Cloud',
            'slug' => 'project-cloud-deploy-' . (time() + 109),
            'lesson_type' => 'project',
            'project_id' => $project10->id,
            'content' => '<p>Hoàn thành project Deploy to Cloud</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Roadmap 9: Game Development
        $game = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Game Development',
            'slug' => 'lo-trinh-game-development',
            'description' => 'Xây dựng game 2D và 3D với Unity hoặc Unreal Engine.',
            'objective' => 'Unity, C#, Unreal Engine, Game Design',
            'category' => 'game',
            'level' => 'intermediate',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $gameStage1 = RoadmapSection::create([
            'roadmap_id' => $game->id,
            'title' => 'Game Development Basics',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $game->id,
            'section_id' => $gameStage1->id,
            'title' => 'Unity và C# cơ bản',
            'slug' => 'unity-basics-' . (time() + 14),
            'lesson_type' => 'video',
            'video_url' => 'https://youtu.be/58qveWELZ-0?si=9W2X6d5ExRZ0Njey',
            'content' => '<p>Bắt đầu với Unity Game Engine.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Project for Game Stage 1
        $project11 = Project::create([
            'roadmap_id' => $game->id,
            'section_id' => $gameStage1->id,
            'title' => 'Project: Simple 2D Game',
            'description' => 'Xây dựng game 2D đơn giản với Unity',
            'starter_code_url' => 'https://github.com/example/unity-2d-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $game->id,
            'section_id' => $gameStage1->id,
            'title' => 'Project: Simple 2D Game',
            'slug' => 'project-2d-game-' . (time() + 110),
            'lesson_type' => 'project',
            'project_id' => $project11->id,
            'content' => '<p>Hoàn thành project Simple 2D Game</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Roadmap 10: UI/UX Design
        $uiux = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình UI/UX Design',
            'slug' => 'lo-trinh-uiux-design',
            'description' => 'Thiết kế giao diện người dùng và trải nghiệm người dùng chuyên nghiệp.',
            'objective' => 'Figma, Adobe XD, User Research, Prototyping',
            'category' => 'frontend',
            'level' => 'beginner',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $uiuxStage1 = RoadmapSection::create([
            'roadmap_id' => $uiux->id,
            'title' => 'UI/UX Fundamentals',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $uiux->id,
            'section_id' => $uiuxStage1->id,
            'title' => 'Nguyên tắc thiết kế UI/UX',
            'slug' => 'uiux-principles-' . (time() + 15),
            'lesson_type' => 'text',
            'content' => '<p>Tìm hiểu các nguyên tắc thiết kế giao diện.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Project for UI/UX Stage 1
        $project12 = Project::create([
            'roadmap_id' => $uiux->id,
            'section_id' => $uiuxStage1->id,
            'title' => 'Project: UI/UX Design Case Study',
            'description' => 'Thiết kế giao diện hoàn chỉnh cho một ứng dụng',
            'starter_code_url' => 'https://github.com/example/uiux-design-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $uiux->id,
            'section_id' => $uiuxStage1->id,
            'title' => 'Project: UI/UX Design Case Study',
            'slug' => 'project-uiux-design-' . (time() + 111),
            'lesson_type' => 'project',
            'project_id' => $project12->id,
            'content' => '<p>Hoàn thành project UI/UX Design Case Study</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        // Roadmap 11: Database Administration
        $database = Roadmap::create([
            'public_id' => Str::uuid()->toString(),
            'author_id' => $author->id,
            'title' => 'Lộ trình Database Administration',
            'slug' => 'lo-trinh-database-administration',
            'description' => 'Quản trị cơ sở dữ liệu MySQL, PostgreSQL, MongoDB và tối ưu hóa performance.',
            'objective' => 'MySQL, PostgreSQL, MongoDB, Database Optimization',
            'category' => 'backend',
            'level' => 'intermediate',
            'status' => 'approved',
            'published_at' => now(),
        ]);

        $dbStage1 = RoadmapSection::create([
            'roadmap_id' => $database->id,
            'title' => 'Database Fundamentals',
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $database->id,
            'section_id' => $dbStage1->id,
            'title' => 'SQL cơ bản và thiết kế database',
            'slug' => 'sql-basics-' . (time() + 16),
            'lesson_type' => 'text',
            'content' => '<p>Học SQL và thiết kế cơ sở dữ liệu quan hệ.</p>',
            'is_published' => true,
            'sort_order' => 1,
        ]);

        // Project for Database Stage 1
        $project13 = Project::create([
            'roadmap_id' => $database->id,
            'section_id' => $dbStage1->id,
            'title' => 'Project: Database Design & Optimization',
            'description' => 'Thiết kế và tối ưu hóa cơ sở dữ liệu cho một hệ thống',
            'starter_code_url' => 'https://github.com/example/database-design-starter',
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $database->id,
            'section_id' => $dbStage1->id,
            'title' => 'Project: Database Design & Optimization',
            'slug' => 'project-database-design-' . (time() + 112),
            'lesson_type' => 'project',
            'project_id' => $project13->id,
            'content' => '<p>Hoàn thành project Database Design & Optimization</p>',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $this->command->info('✅ Seeded: 11 diverse roadmaps with sections and lessons!');
    }
}
