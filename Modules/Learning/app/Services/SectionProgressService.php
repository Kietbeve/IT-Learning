<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\SectionProgress;
use Modules\Learning\Models\RoadmapSection;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\RoadmapLessonProgress;
use Modules\Learning\Models\RoadmapEnrollment;
use Illuminate\Support\Facades\DB;

class SectionProgressService
{
    /**
     * Khởi tạo section progress cho user khi enroll vào roadmap
     */
    public function initializeSectionsForUser(int $userId, int $roadmapId): void
    {
        $sections = RoadmapSection::where('roadmap_id', $roadmapId)
            ->orderBy('sort_order')
            ->get();

        foreach ($sections as $section) {
            $this->createSectionProgress($userId, $section);
        }

        // Unlock first section if it's not locked by prerequisite
        $firstSection = $sections->first();
        if ($firstSection && !$firstSection->prerequisite_section_id) {
            $this->unlockSection($userId, $firstSection->id);
        }
    }

    /**
     * Tạo section progress record
     */
    public function createSectionProgress(int $userId, RoadmapSection $section): SectionProgress
    {
        $totalLessons = $section->lessons()->count();
        
        $status = 'not_started';
        if ($section->is_locked && $section->prerequisite_section_id) {
            $status = 'locked';
        }

        return SectionProgress::firstOrCreate(
            [
                'user_id' => $userId,
                'section_id' => $section->id,
            ],
            [
                'roadmap_id' => $section->roadmap_id,
                'total_lessons' => $totalLessons,
                'completed_lessons' => 0,
                'completion_percent' => 0,
                'status' => $status,
                'time_spent_minutes' => 0,
            ]
        );
    }

    /**
     * Cập nhật tiến độ khi user hoàn thành một lesson
     */
    public function updateProgressOnLessonComplete(int $userId, int $lessonId): void
    {
        $lesson = RoadmapLesson::find($lessonId);
        if (!$lesson || !$lesson->section_id) {
            return;
        }

        $progress = SectionProgress::where('user_id', $userId)
            ->where('section_id', $lesson->section_id)
            ->first();

        if (!$progress) {
            $progress = $this->createSectionProgress($userId, $lesson->section);
        }

        // Đếm số lesson đã hoàn thành trong section
        $completedLessons = RoadmapLessonProgress::where('user_id', $userId)
            ->whereHas('lesson', function ($query) use ($lesson) {
                $query->where('section_id', $lesson->section_id);
            })
            ->where('status', 'completed')
            ->count();

        $progress->completed_lessons = $completedLessons;
        $progress->updateProgress();

        // Check if section is completed
        $section = $lesson->section;
        if ($progress->completion_percent >= $section->min_completion_percent) {
            $this->markSectionCompleted($userId, $lesson->section_id);
        }
    }

    /**
     * Đánh dấu section đã hoàn thành
     */
    public function markSectionCompleted(int $userId, int $sectionId): void
    {
        $progress = SectionProgress::where('user_id', $userId)
            ->where('section_id', $sectionId)
            ->first();

        if ($progress && $progress->status !== 'completed') {
            $progress->status = 'completed';
            $progress->completed_at = now();
            $progress->save();

            // Unlock next section
            $this->unlockNextSection($userId, $sectionId);

            // Fire event
            event(new \Modules\Learning\Events\SectionCompleted($progress));
        }
    }

    /**
     * Mở khóa section tiếp theo sau khi hoàn thành prerequisite
     */
    public function unlockNextSection(int $userId, int $completedSectionId): void
    {
        $section = RoadmapSection::find($completedSectionId);
        if (!$section) {
            return;
        }

        // Tìm các section có prerequisite là section vừa complete
        $dependentSections = RoadmapSection::where('roadmap_id', $section->roadmap_id)
            ->where('prerequisite_section_id', $completedSectionId)
            ->get();

        foreach ($dependentSections as $nextSection) {
            $this->unlockSection($userId, $nextSection->id);
        }
    }

    /**
     * Mở khóa một section cụ thể
     */
    public function unlockSection(int $userId, int $sectionId): void
    {
        $section = RoadmapSection::find($sectionId);
        if (!$section) {
            return;
        }

        // Check if prerequisite is met
        if ($section->prerequisite_section_id) {
            $prerequisiteProgress = SectionProgress::where('user_id', $userId)
                ->where('section_id', $section->prerequisite_section_id)
                ->first();

            if (!$prerequisiteProgress || $prerequisiteProgress->status !== 'completed') {
                return; // Chưa đủ điều kiện unlock
            }
        }

        $progress = SectionProgress::where('user_id', $userId)
            ->where('section_id', $sectionId)
            ->first();

        if (!$progress) {
            $progress = $this->createSectionProgress($userId, $section);
        }

        if ($progress->status === 'locked') {
            $progress->status = 'not_started';
            $progress->save();

            // Send notification
            app(NotificationService::class)->sendSectionUnlockedNotification($userId, $sectionId);
        }
    }

    /**
     * Thêm thời gian học vào section
     */
    public function addTimeSpent(int $userId, int $sectionId, int $minutes): void
    {
        $progress = SectionProgress::where('user_id', $userId)
            ->where('section_id', $sectionId)
            ->first();

        if ($progress) {
            $progress->addTimeSpent($minutes);
        }
    }

    /**
     * Lấy tiến độ của user trong section
     */
    public function getSectionProgress(int $userId, int $sectionId): ?SectionProgress
    {
        return SectionProgress::where('user_id', $userId)
            ->where('section_id', $sectionId)
            ->first();
    }

    /**
     * Lấy tất cả section progress của user trong roadmap
     */
    public function getRoadmapProgress(int $userId, int $roadmapId): array
    {
        $sections = RoadmapSection::where('roadmap_id', $roadmapId)
            ->orderBy('sort_order')
            ->get();

        $progress = [];
        foreach ($sections as $section) {
            $sectionProgress = $this->getSectionProgress($userId, $section->id);
            if (!$sectionProgress) {
                $sectionProgress = $this->createSectionProgress($userId, $section);
            }

            $progress[] = [
                'section' => $section,
                'progress' => $sectionProgress,
                'is_locked' => $section->isLockedForUser($userId),
            ];
        }

        return $progress;
    }

    /**
     * Tính tổng phần trăm hoàn thành roadmap
     */
    public function calculateRoadmapCompletionPercent(int $userId, int $roadmapId): float
    {
        $sections = RoadmapSection::where('roadmap_id', $roadmapId)->get();
        if ($sections->isEmpty()) {
            return 0;
        }

        $totalPercent = SectionProgress::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->sum('completion_percent');

        return round($totalPercent / $sections->count(), 2);
    }

    /**
     * Check if user đã hoàn thành roadmap
     */
    public function isRoadmapCompleted(int $userId, int $roadmapId): bool
    {
        $totalSections = RoadmapSection::where('roadmap_id', $roadmapId)->count();
        
        $completedSections = SectionProgress::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->where('status', 'completed')
            ->count();

        return $totalSections > 0 && $completedSections === $totalSections;
    }
}
