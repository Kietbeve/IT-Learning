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
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => '<p>Lựa chọn framework phù hợp cho Mobile Development.</p>',
            'is_published' => true,
            'sort_order' => 1,
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
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => '<p>Containerize ứng dụng với Docker.</p>',
            'is_published' => true,
            'sort_order' => 2,
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
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => '<p>Làm quen với Python, Pandas và NumPy.</p>',
            'is_published' => true,
            'sort_order' => 1,
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
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => '<p>Các dịch vụ Cloud phổ biến: AWS, Azure, GCP.</p>',
            'is_published' => true,
            'sort_order' => 1,
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
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => '<p>Bắt đầu với Unity Game Engine.</p>',
            'is_published' => true,
            'sort_order' => 1,
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

        $this->command->info('✅ Seeded: 11 diverse roadmaps with sections and lessons!');
    }
}
