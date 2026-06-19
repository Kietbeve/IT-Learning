<?php

namespace Modules\Learning\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\RoadmapEnrollment;
use Modules\Learning\Models\LessonProgress;

class RoadmapService
{
    /**
     * 1. LẤY DANH SÁCH ROADMAP + BỘ LỌC KHỚP 100% DATABASE HIỆN TẠI
     */
    public function getFilteredRoadmaps(array $filters = [], int $perPage = 6)
    {
        // Sử dụng đếm số lượng bản ghi liên kết dựa trên quan hệ của Model
        $query = Roadmap::withCount(['sections', 'lessons']);

        // Bộ lọc tìm kiếm theo tên hoặc mô tả có sẵn trong bảng của bạn
        if (!empty($filters['search'])) {
            $searchTerm = '%' . $filters['search'] . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', $searchTerm)
                  ->orWhere('description', 'like', $searchTerm);
            });
        }

        return $query->latest()->paginate($perPage);
    }

    /**
     * 2. CHI TIẾT ROADMAP VÀ TIẾN ĐỘ HỌC VIÊN
     */
    public function getRoadmapDetails($id)
    {
        $roadmap = Roadmap::with([
            'sections' => function($q) { $q->orderBy('sort_order', 'asc'); },
            'sections.lessons' => function($q) { $q->orderBy('sort_order', 'asc'); }
        ])->withCount(['sections', 'lessons'])->findOrFail($id);

        $userId = Auth::id();
        $isEnrolled = false;
        $progressPercent = 0;
        $completedLessonsCount = 0;
        $completedLessonIds = [];

        if ($userId) {
            $enrollment = RoadmapEnrollment::where('roadmap_id', $id)
                ->where('user_id', $userId)
                ->first();

            if ($enrollment) {
                $isEnrolled = true;
                $completedLessonIds = LessonProgress::where('roadmap_id', $id)
                    ->where('user_id', $userId)
                    ->where('is_completed', true)
                    ->pluck('roadmap_lesson_id')
                    ->toArray();

                $completedLessonsCount = count($completedLessonIds);
                $totalLessons = $roadmap->lessons_count;
                $progressPercent = $totalLessons > 0 ? round(($completedLessonsCount / $totalLessons) * 100, 1) : 0;
            }
        }

        return compact('roadmap', 'isEnrolled', 'progressPercent', 'completedLessonsCount', 'completedLessonIds');
    }

    /**
     * 3. THAM GIA LỘ TRÌNH
     */
    public function enrollUser($roadmapId)
    {
        if (!Auth::check()) return false;

        return RoadmapEnrollment::firstOrCreate([
            'roadmap_id' => $roadmapId,
            'user_id' => Auth::id()
        ], [
            'status' => 'active',
            'progress_percent' => 0,
            'enrolled_at' => now()
        ]);
    }

    /**
     * 4. HOÀN THÀNH BÀI HỌC VÀ CHUYỂN BÀI
     */
    public function completeLesson(int $roadmapId, int $lessonId)
    {
        if (!Auth::check()) return null;
        $userId = Auth::id();

        return DB::transaction(function () use ($roadmapId, $lessonId, $userId) {
            LessonProgress::updateOrCreate([
                'user_id' => $userId,
                'roadmap_id' => $roadmapId,
                'roadmap_lesson_id' => $lessonId
            ], [
                'is_completed' => true,
                'completed_at' => now()
            ]);

            $totalLessons = RoadmapLesson::where('roadmap_id', $roadmapId)->count();
            $completedCount = LessonProgress::where('user_id', $userId)
                ->where('roadmap_id', $roadmapId)
                ->where('is_completed', true)
                ->count();

            $percent = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100, 2) : 0;

            RoadmapEnrollment::where('user_id', $userId)
                ->where('roadmap_id', $roadmapId)
                ->update([
                    'progress_percent' => $percent,
                    'status' => $percent >= 100 ? 'completed' : 'active'
                ]);

            $currentLesson = RoadmapLesson::findOrFail($lessonId);
            return RoadmapLesson::where('roadmap_id', $roadmapId)
                ->where('sort_order', '>', $currentLesson->sort_order)
                ->orderBy('sort_order', 'asc')
                ->first();
        });
    }
}