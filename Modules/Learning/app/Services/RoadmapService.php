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
            
            // Search filter
            if (!empty($filters['search'])) {
                $search = $filters['search'];
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('objective', 'like', "%{$search}%");
                });
            }
            
            // Category filter
            if (!empty($filters['category'])) {
                $query->where('category', $filters['category']);
            }
            
            // Level filter
            if (!empty($filters['level'])) {
                $query->where('level', $filters['level']);
            }
            
            // Sorting
            $sortBy = $filters['sort_by'] ?? 'latest';
            switch ($sortBy) {
                case 'oldest':
                    $query->oldest();
                    break;
                case 'popular':
                    // Giả lập sort theo độ phổ biến (có thể customize sau)
                    $query->orderBy('id', 'asc');
                    break;
                case 'latest':
                default:
                    $query->latest();
                    break;
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
     * TRANG 2: Chi tiết lộ trình với dữ liệu thực từ DB và progress tracking
     */
    public function getRoadmapDetail(mixed $id, $userId = null): array
    {
        try {
            $roadmap = Roadmap::with(['lessons' => function($query) {
                $query->where('is_published', true)->orderBy('sort_order');
            }])->find($id);
        } catch (\Exception $e) {
            $roadmap = null;
        }

        // Nếu không tìm thấy roadmap trong DB, dùng mock data
        if (!$roadmap) {
            return $this->getMockRoadmapDetail($id);
        }

        // Lấy danh sách lessons từ DB
        $lessons = [];
        foreach ($roadmap->lessons as $lesson) {
            $lessons[] = [
                'id' => $lesson->id,
                'title' => $lesson->title,
                'lesson_type' => $lesson->lesson_type ?? 'text',
                'is_preview' => $lesson->is_preview ?? false,
            ];
        }

        // Lấy thông tin enrollment và progress nếu có userId
        $enrollment = null;
        $lessonProgress = [];
        if ($userId) {
            $enrollment = $this->getEnrollmentProgress($userId, $id);
            if ($enrollment) {
                $progressList = $this->getLessonProgressList($userId, $id);
                $lessonProgress = $progressList;
            }
        }

        return [
            'roadmap' => $roadmap,
            'roadmapTitle' => $roadmap->title,
            'lessons' => $lessons,
            'enrollment' => $enrollment,
            'lessonProgress' => $lessonProgress,
            'isEnrolled' => $enrollment ? true : false,
            'progressPercent' => $enrollment ? $enrollment->progress_percent : 0,
        ];
    }

    /**
     * Mock data fallback when DB is empty
     */
    private function getMockRoadmapDetail(mixed $id): array
    {
        $roadmapTemplates = [
            1 => [
                'title' => 'Lộ trình Frontend Developer',
                'description' => 'Làm chủ HTML5, CSS3, JavaScript và ReactJS',
                'titles' => [
                    'Bài 1: Khởi đầu với HTML5 & CSS3 cho Frontend',
                    'Bài 2: Làm chủ Responsive Design với Tailwind CSS',
                    'Bài 3: JavaScript căn bản và DOM Manipulation',
                    'Bài 4: Lập trình Frontend nâng cao với React.js',
                    'Bài 5: Deploy ứng dụng Frontend lên Vercel/Netlify'
                ]
            ],
        ];

        $template = $roadmapTemplates[$id] ?? $roadmapTemplates[1];
        $lessons = [];
        foreach ($template['titles'] as $index => $title) {
            $lessons[] = [
                'id' => $index + 1,
                'title' => $title,
                'lesson_type' => 'text',
                'is_preview' => false,
            ];
        }

        return [
            'roadmap' => null,
            'roadmapTitle' => $template['title'],
            'lessons' => $lessons,
            'enrollment' => null,
            'lessonProgress' => [],
            'isEnrolled' => false,
            'progressPercent' => 0,
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

    /**
     * Check if user is enrolled in a roadmap
     */
    public function checkEnrollment($userId, $roadmapId): bool
    {
        return \Modules\Learning\Models\RoadmapEnrollment::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->exists();
    }

    /**
     * Enroll user in a roadmap
     */
    public function enrollRoadmap($userId, $roadmapId)
    {
        $enrollment = \Modules\Learning\Models\RoadmapEnrollment::firstOrCreate(
            [
                'user_id' => $userId,
                'roadmap_id' => $roadmapId,
            ],
            [
                'status' => 'active',
                'progress_percent' => 0,
                'started_at' => now(),
            ]
        );

        return $enrollment;
    }

    /**
     * Get enrollment progress for a user
     */
    public function getEnrollmentProgress($userId, $roadmapId)
    {
        return \Modules\Learning\Models\RoadmapEnrollment::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->first();
    }

    /**
     * Get lesson progress list for a user in a roadmap
     */
    public function getLessonProgressList($userId, $roadmapId): array
    {
        $progress = \Modules\Learning\Models\RoadmapLessonProgress::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->get()
            ->keyBy('roadmap_lesson_id');

        return $progress->toArray();
    }

    /**
     * Mark a lesson as completed
     */
    public function markLessonCompleted($userId, $roadmapId, $lessonId): bool
    {
        try {
            $progress = \Modules\Learning\Models\RoadmapLessonProgress::updateOrCreate(
                [
                    'user_id' => $userId,
                    'roadmap_id' => $roadmapId,
                    'roadmap_lesson_id' => $lessonId,
                ],
                [
                    'status' => 'completed',
                    'completed_at' => now(),
                    'started_at' => now(),
                ]
            );

            // Update overall enrollment progress
            $this->updateEnrollmentProgress($userId, $roadmapId);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Update enrollment progress percentage based on completed lessons
     */
    public function updateEnrollmentProgress($userId, $roadmapId): void
    {
        // Get total lessons count
        $totalLessons = \Modules\Learning\Models\RoadmapLesson::where('roadmap_id', $roadmapId)
            ->where('is_published', true)
            ->count();

        if ($totalLessons === 0) {
            return;
        }

        // Get completed lessons count
        $completedLessons = \Modules\Learning\Models\RoadmapLessonProgress::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->where('status', 'completed')
            ->count();

        // Calculate progress percentage
        $progressPercent = round(($completedLessons / $totalLessons) * 100);

        // Update enrollment
        $enrollment = \Modules\Learning\Models\RoadmapEnrollment::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->first();

        if ($enrollment) {
            $enrollment->update([
                'progress_percent' => $progressPercent,
                'completed_at' => $progressPercent >= 100 ? now() : null,
            ]);
        }
    }
}