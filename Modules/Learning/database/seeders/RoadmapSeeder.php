<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\RoadmapLesson;
use App\Models\User; // Thêm dòng này để gọi model User

class RoadmapSeeder extends Seeder
{
    public function run(): void
    {
        // 0. TẠO 1 TÀI KHOẢN GIẢNG VIÊN (Nếu chưa có)
        // Lệnh này sẽ kiểm tra xem có ai email này chưa, chưa có thì tự động tạo mới
        $author = User::firstOrCreate(
            ['email' => 'teacher@it-learning.vn'],
            [
                'name' => 'Giảng viên Hệ thống',
                'password' => bcrypt('password123') // Mật khẩu mặc định
            ]
        );

        // 1. Tạo Lộ trình Frontend
        $frontendRoadmap = Roadmap::create([
            'public_id' => 'RM-' . Str::upper(Str::random(6)),
            'author_id' => $author->id, // Lấy động ID của user vừa tạo ở trên
            'title' => 'Lộ trình Frontend Developer',
            'slug' => 'lo-trinh-frontend-developer',
            'short_description' => 'Học HTML, CSS, JavaScript, ReactJS và quy trình xây dựng giao diện chuyên nghiệp chuyên sâu.',
            'description' => 'Lộ trình chi tiết từ con số 0 dành cho các bạn muốn theo đuổi lập trình giao diện.',
            'status' => 'approved',
            'visibility' => 'public',
            'published_at' => now(),
        ]);

        // Tạo Chương 1 cho Frontend
        $feSection1 = RoadmapSection::create([
            'roadmap_id' => $frontendRoadmap->id,
            'title' => 'Chương 1: Xây dựng nền tảng với giao diện Web cơ bản',
            'sort_order' => 1,
        ]);

        // Tạo các bài học cho Chương 1
        RoadmapLesson::create([
            'roadmap_id' => $frontendRoadmap->id,
            'section_id' => $feSection1->id,
            'title' => 'Tổng quan về môi trường Internet và giao thức HTTP/HTTPS',
            'slug' => 'tong-quan-ve-internet-va-http',
            'lesson_type' => 'text',
            'content' => 'Chào mừng bạn đến với bài học đầu tiên! Internet hoạt động dựa trên mô hình Client-Server...',
            'is_required' => true,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        RoadmapLesson::create([
            'roadmap_id' => $frontendRoadmap->id,
            'section_id' => $feSection1->id,
            'title' => 'Cấu trúc layout website bằng thẻ HTML5 và định dạng CSS3',
            'slug' => 'cau-truc-layout-voi-html5-css3',
            'lesson_type' => 'video',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
            'content' => 'Xem kĩ video hướng dẫn và thực hành lại bố cục grid/flexbox nhé.',
            'is_required' => true,
            'is_published' => true,
            'sort_order' => 2,
        ]);


        // 2. Tạo Lộ trình Backend
        $backendRoadmap = Roadmap::create([
            'public_id' => 'RM-' . Str::upper(Str::random(6)),
            'author_id' => $author->id, // Lấy động ID
            'title' => 'Lộ trình Backend Developer',
            'slug' => 'lo-trinh-backend-developer',
            'short_description' => 'Chinh phục Laravel, MySQL, Restful API và tư duy thiết kế kiến trúc hệ thống chuyên sâu.',
            'description' => 'Lộ trình giúp bạn làm chủ logic phía máy chủ và xây dựng hệ thống lớn ổn định.',
            'status' => 'approved',
            'visibility' => 'public',
            'published_at' => now(),
        ]);

        // Tạo Chương 1 cho Backend
        $beSection1 = RoadmapSection::create([
            'roadmap_id' => $backendRoadmap->id,
            'title' => 'Chương 1: Tổng quan nhập môn Lập trình PHP căn bản',
            'sort_order' => 1,
        ]);

        // Tạo bài học cho Backend
        RoadmapLesson::create([
            'roadmap_id' => $backendRoadmap->id,
            'section_id' => $beSection1->id,
            'title' => 'Cài đặt môi trường XAMPP và cú pháp PHP core căn bản',
            'slug' => 'cai-dat-moi-truong-va-cu-phap-php-core',
            'lesson_type' => 'text',
            'content' => 'Bài học này hướng dẫn các bạn cách khai báo biến, mảng và các vòng lặp cơ bản trong PHP.',
            'is_required' => true,
            'is_published' => true,
            'sort_order' => 1,
        ]);
    }
}