<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\Roadmap; // Import Model Roadmap nếu có

class RoadmapService
{
    /**
     * TRANG 1: Lấy danh sách lộ trình (Tương thích dạng Object và đầy đủ mô tả)
     */
    public function getFilteredRoadmaps(array $filters, int $perPage)
    {
        try {
            $hasData = Roadmap::exists();
        } catch (\Exception $e) {
            $hasData = false;
        }

        if ($hasData) {
            $query = Roadmap::query();
            if (!empty($filters['sort']) && $filters['sort'] === 'oldest') {
                $query->oldest();
            } else {
                $query->latest();
            }
            return $query->paginate($perPage);
        }

        // ÉP DỮ LIỆU MẪU SANG DẠNG OBJECT (stdClass) ĐỂ KHÔNG MẤT TIÊU ĐỀ/MÔ TẢ TRÊN UI
        $sampleData = [
            ['id' => 1, 'title' => 'Lộ trình Frontend Developer', 'description' => 'Làm chủ HTML5, CSS3, JavaScript và thư viện ReactJS để xây dựng giao diện ứng dụng web.', 'image' => 'frontend.png', 'created_at' => now()],
            ['id' => 2, 'title' => 'Lộ trình Backend Developer', 'description' => 'Học ngôn ngữ PHP, làm chủ framework Laravel và thiết kế cơ sở dữ liệu MySQL chuẩn RESTful API.', 'image' => 'backend.png', 'created_at' => now()->subDay()],
            ['id' => 3, 'title' => 'Lộ trình Fullstack Developer', 'description' => 'Sự kết hợp hoàn hảo giữa tư duy giao diện Frontend và kiến trúc vận hành hệ thống Backend.', 'image' => 'fullstack.png', 'created_at' => now()->subDays(2)],
            ['id' => 4, 'title' => 'Lập trình di động (Mobile)', 'description' => 'Xây dựng ứng dụng đa nền tảng cho cả iOS và Android bằng Flutter hoặc React Native.', 'image' => 'mobile.png', 'created_at' => now()->subDays(3)],
            ['id' => 5, 'title' => 'Lộ trình DevOps Engineer', 'description' => 'Triển khai chu trình CI/CD, quản lý hạ tầng đám mây Cloud và đóng gói ứng dụng với Docker.', 'image' => 'devops.png', 'created_at' => now()->subDays(4)],
            ['id' => 6, 'title' => 'Lộ trình Data Science & AI', 'description' => 'Phân tích dữ liệu lớn bằng Python và xây dựng mô hình trí tuệ nhân tạo Học máy (Machine Learning).', 'image' => 'ai.png', 'created_at' => now()->subDays(5)],
        ];

        // Chuyển mảng thành mảng các Object
        $objectRoadmaps = collect($sampleData)->map(function ($item) {
            return (object) $item;
        });

        if (!empty($filters['sort']) && $filters['sort'] === 'oldest') {
            $objectRoadmaps = $objectRoadmaps->sortBy('created_at');
        } else {
            $objectRoadmaps = $objectRoadmaps->sortByDesc('created_at');
        }

        $page = request()->get('page', 1);
        return new \Illuminate\Pagination\LengthAwarePaginator(
            $objectRoadmaps->forPage($page, $perPage)->values(),
            $objectRoadmaps->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
    }
    /**
     * TRANG 2: Chi tiết lộ trình (Danh sách bài học mẫu hoặc thật)
     */
    public function getRoadmapDetail(mixed $id): array
    {
        $roadmapTitle = 'Lộ trình Học tập';
        $lessons = [];

        // Thử tìm lộ trình trong DB thật trước
        try {
            $roadmap = Roadmap::find($id);
        } catch (\Exception $e) {
            $roadmap = null;
        }

        if ($roadmap) {
            $roadmapTitle = $roadmap->title;
            // Giả lập sinh ra các bài học ăn theo tên DB thật
            for ($i = 1; $i <= 5; $i++) {
                $lessons[] = [
                    'id' => $i,
                    'title' => "Bài {$i}: Nội dung kiến thức nâng cao thuộc " . $roadmapTitle
                ];
            }
        } else {
            // Nếu DB trống -> Lấy mảng bài học mẫu đồng bộ
            $roadmapTemplates = [
                1 => [
                    'title' => 'Lộ trình Frontend Developer',
                    'titles' => [
                        1 => 'Bài 1: Khởi đầu với HTML5 & CSS3 cho Frontend',
                        2 => 'Bài 2: Làm chủ Responsive Design với Tailwind CSS',
                        3 => 'Bài 3: JavaScript căn bản và DOM Manipulation',
                        4 => 'Bài 4: Lập trình Frontend nâng cao với React.js',
                        5 => 'Bài 5: Deploy ứng dụng Frontend lên Vercel/Netlify'
                    ]
                ],
                2 => [
                    'title' => 'Lộ trình Backend Developer',
                    'titles' => [
                        1 => 'Bài 1: Cài đặt môi trường và Cơ bản về PHP/Node.js',
                        2 => 'Bài 2: Thiết kế Cơ sở dữ liệu quan hệ với MySQL',
                        3 => 'Bài 3: Xây dựng RESTful API chuẩn quốc tế',
                        4 => 'Bài 4: Xác thực người dùng nâng cao với JWT/Passport',
                        5 => 'Bài 5: Quản lý mã nguồn Backend và đẩy lên Server'
                    ]
                ],
                3 => [
                    'title' => 'Lộ trình Fullstack Developer',
                    'titles' => [
                        1 => 'Bài 1: Kiến trúc hệ thống Fullstack Web',
                        2 => 'Bài 2: Kết nối Giao diện Frontend với API Backend',
                        3 => 'Bài 3: Quản lý State đồng bộ giữa Client và Server',
                        4 => 'Bài 4: Tối ưu hóa hiệu năng và bảo mật ứng dụng',
                        5 => 'Bài 5: Triển khai dự án Fullstack bằng Docker'
                    ]
                ],
                4 => [
                    'title' => 'Lập trình di động (Mobile)',
                    'titles' => [
                        1 => 'Bài 1: Làm quen với Flutter / React Native',
                        2 => 'Bài 2: Thiết kế giao diện Mobile Widgets linh hoạt',
                        3 => 'Bài 3: Xử lý trạng thái và dữ liệu cục bộ',
                        4 => 'Bài 4: Tích hợp API và Push Notification',
                        5 => 'Bài 5: Đóng gói và phát hành ứng dụng lên Store'
                    ]
                ],
                5 => [
                    'title' => 'Lộ trình DevOps Engineer',
                    'titles' => [
                        1 => 'Bài 1: Hệ điều hành Linux và Scripting cơ bản',
                        2 => 'Bài 2: Đóng gói ứng dụng với Docker Containers',
                        3 => 'Bài 3: Xây dựng chu trình tự động hóa CI/CD',
                        4 => 'Bài 4: Quản trị hạ tầng Cloud (AWS / Google Cloud)',
                        5 => 'Bài 5: Giám sát hệ thống với Prometheus & Grafana'
                    ]
                ],
                6 => [
                    'title' => 'Lộ trình Data Science & AI',
                    'titles' => [
                        1 => 'Bài 1: Lập trình Python cho phân tích dữ liệu',
                        2 => 'Bài 2: Khai phá dữ liệu và Thống kê ứng dụng',
                        3 => 'Bài 3: Thuật toán Học máy (Machine Learning)',
                        4 => 'Bài 4: Nhập môn Trí tuệ nhân tạo & Deep Learning',
                        5 => 'Bài 5: Triển khai mô hình AI vào sản phẩm thực tế'
                    ]
                ]
            ];

            if (isset($roadmapTemplates[$id])) {
                $roadmapTitle = $roadmapTemplates[$id]['title'];
                foreach ($roadmapTemplates[$id]['titles'] as $lId => $title) {
                    $lessons[] = [
                        'id' => $lId,
                        'title' => $title
                    ];
                }
            }
        }

        return [
            'roadmapTitle' => $roadmapTitle,
            'lessons' => $lessons
        ];
    }

    /**
     * TRANG 3: Cung cấp thông tin chi tiết bài học và đính kèm File PDF động
     */
    public function getLessonDetail(mixed $roadmapId, mixed $lessonId): array
    {
        // Gọi lại hàm chi tiết lộ trình để lấy chính xác tiêu đề bài học
        $roadmapData = $this->getRoadmapDetail($roadmapId);
        $categoryName = $roadmapData['roadmapTitle'] ?? 'Học tập';
        $lessons = $roadmapData['lessons'] ?? [];
        
        $lessonTitle = 'Nội dung bài học chi tiết';

        foreach ($lessons as $lesson) {
            if ($lesson['id'] == $lessonId) {
                $lessonTitle = $lesson['title'];
                break;
            }
        }

        // Tự động map 5 file PDF bạn mới thêm vào riêng cho lộ trình Frontend (ID = 1)
        $pdfFile = '';
        if ($roadmapId == 1) {
            $pdfMap = [
                1 => 'Bai_1_HTML5_CSS3.pdf',
                2 => 'Bai_2_Tailwind_CSS.pdf',
                3 => 'Bai_3_JavaScript_DOM.pdf',
                4 => 'Bai_4_ReactJS.pdf',
                5 => 'Bai_5_Deploy.pdf',
            ];
            $pdfFile = $pdfMap[$lessonId] ?? '';
        }

        return [
            'categoryName' => $categoryName,
            'lessonTitle' => $lessonTitle,
            'pdfFile' => $pdfFile
        ];
    }
}