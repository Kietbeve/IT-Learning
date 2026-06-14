<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\RoadmapEnrollment;
use Modules\Learning\Models\LessonProgress;

class LearningController extends Controller
{
    public function index()
    {
        $roadmaps = Roadmap::with(['author'])->where('status', 'approved')->where('visibility', 'public')->get();
        return view('learning::layouts.roadmap-list', compact('roadmaps'));
    }

    public function show($id)
    {
        $roadmap = Roadmap::with([
            'sections' => fn($q) => $q->orderBy('sort_order', 'asc'),
            'sections.lessons' => fn($q) => $q->where('is_published', true)->orderBy('sort_order', 'asc')
        ])->where('status', 'approved')->findOrFail($id);

        $enrollment = Auth::check() ? RoadmapEnrollment::where('roadmap_id', $id)->where('user_id', Auth::id())->first() : null;
        return view('learning::layouts.roadmap-detail', compact('roadmap', 'enrollment'));
    }

    public function learn($id, $lesson_id = null)
    {
        $user = Auth::user();
        $roadmap = Roadmap::with([
            'sections' => fn($q) => $q->orderBy('sort_order', 'asc'),
            'sections.lessons' => fn($q) => $q->where('is_published', true)->orderBy('sort_order', 'asc')
        ])->where('status', 'approved')->findOrFail($id);

        $enrollment = RoadmapEnrollment::firstOrCreate(['roadmap_id' => $id, 'user_id' => $user->id], ['status' => 'active', 'progress_percent' => 0, 'started_at' => now()]);

        if ($lesson_id) {
            $currentLesson = RoadmapLesson::where('roadmap_id', $id)->where('is_published', true)->findOrFail($lesson_id);
        } else {
            $firstSection = $roadmap->sections->first();
            $currentLesson = $firstSection ? $firstSection->lessons->first() : null;
            if (!$currentLesson) return redirect()->route('learning.roadmaps.show', $id)->with('error', 'Chưa có bài học.');
        }

        $progress = LessonProgress::firstOrCreate(['enrollment_id' => $enrollment->id, 'lesson_id' => $currentLesson->id, 'user_id' => $user->id], ['status' => 'in_progress', 'started_at' => now()]);
        $completedLessonIds = LessonProgress::where('enrollment_id', $enrollment->id)->where('status', 'completed')->pluck('lesson_id')->toArray();

        return view('learning::layouts.lesson-view', compact('roadmap', 'currentLesson', 'enrollment', 'progress', 'completedLessonIds'));
    }

    public function completeLesson($lesson_id)
    {
        $user = Auth::user();
        $lesson = RoadmapLesson::findOrFail($lesson_id);
        $enrollment = RoadmapEnrollment::where('roadmap_id', $lesson->roadmap_id)->where('user_id', $user->id)->firstOrFail();

        LessonProgress::updateOrCreate(['enrollment_id' => $enrollment->id, 'lesson_id' => $lesson->id, 'user_id' => $user->id], ['status' => 'completed', 'completed_at' => now()]);

        $totalLessons = RoadmapLesson::where('roadmap_id', $lesson->roadmap_id)->where('is_published', true)->count();
        $completedLessons = LessonProgress::where('enrollment_id', $enrollment->id)->where('status', 'completed')->count();
        $percent = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0;
        
        $enrollment->update(['progress_percent' => $percent, 'status' => $percent >= 100 ? 'completed' : 'active', 'completed_at' => $percent >= 100 ? now() : null]);

        $nextLesson = RoadmapLesson::where('roadmap_id', $lesson->roadmap_id)->where('is_published', true)->where('sort_order', '>', $lesson->sort_order)->orderBy('sort_order', 'asc')->first();

        if ($nextLesson) return redirect()->route('learning.roadmaps.learn', [$lesson->roadmap_id, $nextLesson->id])->with('success', 'Chuyển bài tiếp theo.');
        return redirect()->route('learning.roadmaps.learn', $lesson->roadmap_id)->with('success', 'Bạn đã hoàn thành lộ trình!');
    }
}