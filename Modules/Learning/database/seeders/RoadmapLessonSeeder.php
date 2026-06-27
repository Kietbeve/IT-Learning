<?php

namespace Modules\Learning\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\RoadmapLesson;

class RoadmapLessonSeeder extends Seeder
{
    /**
     * Seed 11 roadmaps with exactly 4 lessons each (2 video, 2 text)
     */
    public function run(): void
    {
        // 1. Danh sách 11 lộ trình chuẩn kèm theo video và nội dung riêng biệt cho từng ngành
        // Lưu ý: Category được chuyển về chữ thường để khớp chính xác với RoadmapSeeder.php gốc của bạn
        $roadmapsList = [
            [
                'title' => 'Lộ trình Frontend Developer', 'category' => 'frontend',
                'v1_url' => 'https://www.youtube.com/embed/qz0aGYrrlhU',
                'v2_url' => 'https://www.youtube.com/embed/JJSoEo8JSnc',
                'c1' => '<h2>Tổng quan Frontend</h2><p>Tìm hiểu về HTML, CSS, JavaScript và cách trình duyệt biên dịch giao diện hiển thị.</p>',
                'c2' => '<h2>Tài liệu HTML5 & CSS3</h2><p>Đọc hiểu các thẻ Semantic chuẩn SEO, Box Model, Flexbox và Grid Layout nâng cao.</p>',
                'c3' => '<h2>Thực hành Giao diện mẫu</h2><p>Xem video hướng dẫn từng bước xây dựng một trang Landing Page Responsive chạy mượt mà.</p>',
                'c4' => '<h2>Tổng kết kiến thức cơ bản</h2><p>Hệ thống hóa kiến thức nền tảng và chuẩn bị lộ trình học thư viện Single Page Application ReactJS.</p>',
            ],
            [
                'title' => 'Lộ trình Backend Developer', 'category' => 'backend',
                'v1_url' => 'https://youtu.be/tN6oJu2DqCM?si=XB6-WfOHbZKXwtGQ',
                'v2_url' => 'https://www.youtube.com/embed/bMknfKXIFA8',
                'c1' => '<h2>Kiến trúc hệ thống Web</h2><p>Hiểu cơ chế vận hành của mô hình Client-Server, cách tiếp nhận và phản hồi Request.</p>',
                'c2' => '<h2>Lý thuyết Lập trình hướng đối tượng</h2><p>Nắm vững 4 tính chất cốt lõi OOP: Đóng gói, Kế thừa, Đa hình và Trừu tượng trong PHP.</p>',
                'c3' => '<h2>Thực hành RESTful API với Laravel</h2><p>Xem hướng dẫn chi tiết viết các phương thức CRUD chuẩn hóa định dạng JSON trả về.</p>',
                'c4' => '<h2>Tối ưu hóa Database Eloquent ORM</h2><p>Phân tích các kỹ thuật khắc phục lỗi N+1 query và cơ chế quản lý index, caching dữ liệu.</p>',
            ],
            [
                'title' => 'Lộ trình Fullstack Developer', 'category' => 'fullstack',
                'v1_url' => 'https://www.youtube.com/embed/zJSY8tbf_ys',
                'v2_url' => 'https://youtu.be/aOsSC6NseZw?si=NWu_xPzbS_fHn0Kp',
                'c1' => '<h2>Hệ sinh thái Fullstack</h2><p>Khám phá cách tổ chức vận hành đồng thời giữa ứng dụng Client và cụm Server API độc lập.</p>',
                'c2' => '<h2>Lý thuyết Bảo mật Authentication</h2><p>Tìm hiểu cơ chế xác thực người dùng an toàn thông qua JWT (JSON Web Token) và Laravel Sanctum.</p>',
                'c3' => '<h2>Thực hành Kết nối dữ liệu</h2><p>Lập trình sử dụng thư viện Axios kết nối, đồng bộ dữ liệu giữa React Frontend và API Backend.</p>',
                'c4' => '<h2>Quy trình triển khai sản phẩm</h2><p>Tổng hợp các bước build mã nguồn, cấu hình Web Server Nginx và kích hoạt chứng chỉ SSL lên VPS.</p>',
            ],
            [
                'title' => 'Lộ trình Mobile Development', 'category' => 'mobile',
                'v1_url' => 'https://youtu.be/w0SQGCt-6Ro?si=5T-xPZL70n1JxXtk',
                'v2_url' => 'https://youtu.be/w0SQGCt-6Ro?si=5T-xPZL70n1JxXtk',
                'c1' => '<h2>Thị trường Lập trình Di động</h2><p>So sánh điểm mạnh và hiệu năng giữa các công nghệ Native App và Cross-platform.</p>',
                'c2' => '<h2>Lý thuyết Quản lý trạng thái State</h2><p>Hiểu cách dòng dữ liệu di chuyển xuyên suốt các màn hình thiết bị (Provider, Bloc, Redux).</p>',
                'c3' => '<h2>Thực hành Xây dựng UI mượt mà</h2><p>Xem hướng dẫn kéo thả component, tối ưu hóa danh sách cuộn mượt mà trên cả iOS và Android.</p>',
                'c4' => '<h2>Đóng gói và Phát hành Ứng dụng</h2><p>Các tiêu chuẩn kiểm duyệt và quy trình đưa sản phẩm lên Google Play và App Store công khai.</p>',
            ],
            [
                'title' => 'Lộ trình DevOps Engineer', 'category' => 'devops',
                'v1_url' => 'https://youtu.be/Xrgk023l4lI?si=bMuXuYrm9H3ypz5D',
                'v2_url' => 'https://www.youtube.com/embed/3c-iBn73dDE',
                'c1' => '<h2>Tổng quan Văn hóa DevOps</h2><p>Tìm hiểu vai trò kết nối giữa đội ngũ Phát triển phần mềm và Vận hành hệ thống máy chủ.</p>',
                'c2' => '<h2>Quản trị Hệ thống Linux & Container</h2><p>Nắm rõ câu lệnh shell thông dụng, cấu trúc thư mục Linux và lý thuyết đóng gói Dockerfile.</p>',
                'c3' => '<h2>Thực hành Tự động hóa CI/CD</h2><p>Thiết lập đường ống tự động chạy Unit Test và tự động Deploy dự án thông qua GitHub Actions.</p>',
                'c4' => '<h2>Điều phối Cụm máy chủ Kubernetes</h2><p>Quản lý hệ thống microservices quy mô lớn với cơ chế Auto-scaling tự động tăng giảm tài nguyên.</p>',
            ],
            [
                'title' => 'Lộ trình Data Science & AI', 'category' => 'data-science',
                'v1_url' => 'https://www.youtube.com/embed/ua-CiDNNj30',
                'v2_url' => 'https://www.youtube.com/live/xPh5ihBWang?si=FVEdZaLsZHg1nOgD',
                'c1' => '<h2>Kỷ nguyên Khoa học dữ liệu</h2><p>Tìm hiểu toán ứng dụng, đại số tuyến tính và vị thế của ngôn ngữ Python trong lĩnh vực AI.</p>',
                'c2' => '<h2>Xử lý và Làm sạch số liệu</h2><p>Kỹ thuật sử dụng thư viện Pandas, NumPy để xử lý dữ liệu bị khuyết thiếu, nhiễu thông tin.</p>',
                'c3' => '<h2>Huấn luyện Mô hình Machine Learning</h2><p>Sử dụng Scikit-learn xây dựng các bài toán dự đoán, phân loại dữ liệu dựa trên mô hình toán học.</p>',
                'c4' => '<h2>Tổng quan Deep Learning mạng Neural</h2><p>Tiếp cận thư viện TensorFlow để thiết lập cấu trúc mạng nơ-ron nhân tạo nhận diện hình ảnh.</p>',
            ],
            [
                'title' => 'Lộ trình Cybersecurity', 'category' => 'security',
                'v1_url' => 'https://www.youtube.com/embed/inWWhr5tnEA',
                'v2_url' => 'https://youtu.be/AOUSI1b7DAw?si=RqguVmTczHsSCg7O',
                'c1' => '<h2>An toàn thông tin thời đại số</h2><p>Nhận diện các hình thức tấn công mạng phổ biến và các nguyên tắc phòng thủ cơ bản.</p>',
                'c2' => '<h2>Tiêu chuẩn lỗ hổng OWASP Top 10</h2><p>Phân tích sâu cơ chế hoạt động của các lỗ hổng nguy hiểm bậc nhất như SQL Injection, XSS.</p>',
                'c3' => '<h2>Thực hành Rà quét lỗ hổng hệ thống</h2><p>Xem cách ứng dụng các công cụ chuyên dụng trên hệ điều hành Kali Linux để kiểm thử bảo mật.</p>',
                'c4' => '<h2>Mật mã hóa và Hash mật khẩu</h2><p>Tìm hiểu các thuật toán mã hóa đối xứng, bất đối xứng và hàm băm bảo mật dữ liệu người dùng.</p>',
            ],
            [
                'title' => 'Lộ trình Cloud Computing', 'category' => 'cloud',
                'v1_url' => 'https://www.youtube.com/embed/M988_fsOSWo',
                'v2_url' => 'https://www.youtube.com/embed/2HiY87wTjE4',
                'c1' => '<h2>Bản chất Điện toán đám mây</h2><p>Phân biệt chi tiết các mô hình dịch vụ hạ tầng phổ biến hiện nay: IaaS, PaaS, SaaS.</p>',
                'c2' => '<h2>Thiết kế hạ tầng mạng ảo VPC</h2><p>Lý thuyết phân chia dải IP, thiết lập Subnet công khai, nội bộ và cấu hình nhóm bảo mật mạng.</p>',
                'c3' => '<h2>Thực hành Khởi tạo máy chủ toán đám AWS</h2><p>Xem hướng dẫn cấu hình chi tiết máy chủ ảo EC2 và dịch vụ lưu trữ dữ liệu đám mây S3.</p>',
                'c4' => '<h2>Phân quyền chặt chẽ với IAM Policy</h2><p>Áp dụng nguyên tắc đặc quyền tối thiểu để kiểm soát quyền hạn truy cập của từng tài khoản hệ thống.</p>',
            ],
            [
                'title' => 'Lộ trình Game Development', 'category' => 'game',
                'v1_url' => 'https://youtu.be/_eK26atXTds?si=YE0vKgpw8HSn4Eju',
                'v2_url' => 'https://youtu.be/TgqAqniukTg?si=TEveGQNDSKPDarMY',
                'c1' => '<h2>Kiến trúc Engine làm Game</h2><p>Giới thiệu không gian làm việc của Unity Engine, cấu trúc GameObject và vòng đời Script C#.</p>',
                'c2' => '<h2>Quản lý Assets và Thiết kế màn chơi</h2><p>Phương pháp sắp đặt môi trường 2D/3D, nhập mô hình nhân vật và tối ưu hóa file đồ họa đầu vào.</p>',
                'c3' => '<h2>Thực hành Lập trình Vật lý va chạm</h2><p>Viết mã xử lý lực nhảy, di chuyển nhân vật và bắt sự kiện va chạm bằng Rigidbody và Collider.</p>',
                'c4' => '<h2>Thiết kế giao diện UI và Đóng gói Game</h2><p>Xây dựng thanh máu, Menu điều khiển và xuất file cài đặt chạy tối ưu cho đa nền tảng.</p>',
            ],
            [
                'title' => 'Lộ trình UI/UX Design', 'category' => 'frontend', // Khớp với category UI/UX trong RoadmapSeeder
                'v1_url' => 'https://youtu.be/c9Wg6Cb_YlU?si=j3xfxlI8fD9egFB4',
                'v2_url' => 'https://youtu.be/c9Wg6Cb_YlU?si=j3xfxlI8fD9egFB4',
                'c1' => '<h2>Tâm lý học hành vi người dùng</h2><p>Nghiên cứu các quy tắc bố cục thị giác, khoảng trắng và phân cấp màu sắc trong thiết kế.</p>',
                'c2' => '<h2>Xây dựng bản phác thảo Wireframe</h2><p>Quy trình định hình bộ khung cấu trúc tính năng sản phẩm trước khi đổ màu thiết kế chi tiết.</p>',
                'c3' => '<h2>Thực hành Dựng Prototype tương tác động</h2><p>Sử dụng Figma kết nối các màn hình, tạo hiệu ứng chuyển động mượt mà như một ứng dụng thật.</p>',
                'c4' => '<h2>Đóng gói thư viện Design System</h2><p>Quy chuẩn hóa mã màu, font chữ, kích cỡ component để bàn giao chuẩn chỉnh cho đội ngũ Developer.</p>',
            ],
            [
                'title' => 'Lộ trình Database Administration', 'category' => 'backend', // Khớp với category Database Admin trong RoadmapSeeder
                'v1_url' => 'https://www.youtube.com/embed/HXV3zeQKqGY',
                'v2_url' => 'https://youtu.be/VILXwtD-41Y?si=k6Cyau6DEbnegY1C',
                'c1' => '<h2>Lý thuyết Mô hình hóa dữ liệu</h2><p>Phân tích thiết kế sơ đồ thực thể liên kết ERD và các quy tắc chuẩn hóa bảng dữ liệu.</p>',
                'c2' => '<h2>Tối ưu hóa Index chỉ mục truy vấn</h2><p>Tìm hiểu bản chất cấu trúc B-Tree giúp tối ưu tốc độ tìm kiếm hàng triệu bản ghi trong tích tắc.</p>',
                'c3' => '<h2>Thực hành Cấu hình Backup tự động</h2><p>Thiết lập kịch bản sao lưu định kỳ phòng chống rủi ro hỏng hóc hoặc mất mát dữ liệu.</p>',
                'c4' => '<h2>Giám sát Nhật ký Security Audit Log</h2><p>Bảo mật lớp lưu trữ dữ liệu cuối, ghi vết và cảnh báo các hành vi can thiệp trái phép vào DB.</p>',
            ],
        ];

        foreach ($roadmapsList as $data) {
            // 2. Tìm hoặc tạo mới Lộ trình nếu chưa có (Khớp theo tiêu đề độc nhất)
            $roadmap = Roadmap::firstOrCreate(
                ['title' => $data['title']],
                [
                    'public_id' => Str::uuid()->toString(),
                    'description' => 'Khóa học nền tảng dành cho ' . $data['title'],
                    'category' => $data['category'],
                    'level' => 'beginner',
                    'status' => 'approved',
                ]
            );

            // 3. Tìm hoặc tạo mới 1 Chương học (Section) cho mỗi lộ trình
            $section = RoadmapSection::firstOrCreate(
                ['roadmap_id' => $roadmap->id, 'title' => 'Chương 1: Kiến thức nền tảng'],
                ['sort_order' => 1]
            );

            // 4. Bổ sung đúng 4 bài học - GIỮ NGUYÊN CÁCH ĐẶT TÊN - Chỉ thay thế video/content động theo ngành
            
            // BÀI 1: VIDEO (Dynamic URL & Content)
            RoadmapLesson::updateOrCreate(
                ['roadmap_id' => $roadmap->id, 'title' => 'Bài 1: Tổng quan ' . $roadmap->title],
                [
                    'section_id' => $section->id,
                    'slug' => Str::slug('Bai 1 Tong quan ' . $roadmap->id),
                    'lesson_type' => 'video',
                    'video_url' => $data['v1_url'], // Lấy từ cấu hình mảng riêng biệt
                    'content' => $data['c1'],       // Lấy từ cấu hình mảng riêng biệt
                    'is_published' => true,
                    'sort_order' => 1,
                ]
            );

            // BÀI 2: TEXT (Dynamic Content)
            RoadmapLesson::updateOrCreate(
                ['roadmap_id' => $roadmap->id, 'title' => 'Bài 2: Tài liệu lý thuyết ' . $roadmap->title],
                [
                    'section_id' => $section->id,
                    'slug' => Str::slug('Bai 2 Ly thuyet ' . $roadmap->id),
                    'lesson_type' => 'text',
                    'content' => $data['c2'],       // Lấy từ cấu hình mảng riêng biệt
                    'is_published' => true,
                    'sort_order' => 2,
                ]
            );

            // BÀI 3: VIDEO (Dynamic URL & Content)
            RoadmapLesson::updateOrCreate(
                ['roadmap_id' => $roadmap->id, 'title' => 'Bài 3: Thực hành ' . $roadmap->title],
                [
                    'section_id' => $section->id,
                    'slug' => Str::slug('Bai 3 Thuc hanh ' . $roadmap->id),
                    'lesson_type' => 'video',
                    'video_url' => $data['v2_url'], // Lấy từ cấu hình mảng riêng biệt
                    'content' => $data['c3'],       // Lấy từ cấu hình mảng riêng biệt
                    'is_published' => true,
                    'sort_order' => 3,
                ]
            );

            // BÀI 4: TEXT (Dynamic Content)
            RoadmapLesson::updateOrCreate(
                ['roadmap_id' => $roadmap->id, 'title' => 'Bài 4: Tổng kết ' . $roadmap->title],
                [
                    'section_id' => $section->id,
                    'slug' => Str::slug('Bai 4 Tong ket ' . $roadmap->id),
                    'lesson_type' => 'text',
                    'content' => $data['c4'],       // Lấy từ cấu hình mảng riêng biệt
                    'is_published' => true,
                    'sort_order' => 4,
                ]
            );
        }

        $this->command->info('✅ Seeded hoàn tất: 11 lộ trình chuẩn, giữ nguyên cấu trúc tên bài học gốc của bạn nhưng không còn lặp lại video!');
    }
}