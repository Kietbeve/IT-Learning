<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Modules\Learning\Services\RoadmapService; 

class LearningController extends Controller
{
    protected RoadmapService $roadmapService;

    public function __construct(RoadmapService $roadmapService)
    {
        $this->roadmapService = $roadmapService;
    }

    /**
     * TRANG 1: Danh sách lộ trình
     */
    public function index(Request $request): View
    {
        return view('learning::layouts.roadmap-list', [
            'roadmaps' => $this->roadmapService->getFilteredRoadmaps($request->all(), 6)
        ]);
    }

    /**
     * TRANG 2: Chi tiết lộ trình
     */
    public function show(mixed $id): View
    {
        $userId = auth()->check() ? auth()->id() : null;
        
        return view('learning::layouts.roadmap-detail', array_merge(
            ['id' => $id], 
            $this->roadmapService->getRoadmapDetail($id, $userId)
        ));
    }

    /**
     * TRANG 3: Nội dung chi tiết bài học
     */
    public function showLesson(mixed $roadmapId, mixed $lessonId): View
    {
        return view('learning::layouts.lesson-show', array_merge(
            ['roadmapId' => $roadmapId, 'lessonId' => $lessonId],
            $this->roadmapService->getLessonDetail($roadmapId, $lessonId)
        ));
    }

    /**
     * Enroll user in a roadmap
     */
    public function enroll(Request $request, mixed $id)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đăng ký lộ trình');
        }

        $userId = auth()->id();
        
        // Check if already enrolled
        if ($this->roadmapService->checkEnrollment($userId, $id)) {
            return back()->with('info', 'Bạn đã đăng ký lộ trình này rồi');
        }

        // Enroll user
        $enrollment = $this->roadmapService->enrollRoadmap($userId, $id);

        if ($enrollment) {
            return back()->with('success', 'Đăng ký lộ trình thành công!');
        }

        return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
    }

    /**
     * TRANG 4: Không gian học tập - Learning workspace
     */
    public function learn(mixed $roadmapId, mixed $lessonId = null)
    {
        // Check authentication
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để học');
        }

        $userId = auth()->id();

        // Check enrollment
        if (!$this->roadmapService->checkEnrollment($userId, $roadmapId)) {
            return redirect()->route('learning.roadmaps.show', $roadmapId)
                ->with('error', 'Bạn chưa đăng ký lộ trình này');
        }

        // Load roadmap with sections and lessons (including standalone lessons without sections)
        $roadmap = \Modules\Learning\Models\Roadmap::with([
            'sections.lessons' => function($query) {
                $query->where('is_published', true)->orderBy('sort_order');
            },
            'lessons' => function($query) {
                $query->where('is_published', true)->orderBy('sort_order');
            }
        ])->findOrFail($roadmapId);

        // Get all lessons in order - from sections AND standalone
        $lessonsFromSections = $roadmap->sections->flatMap(function($section) {
            return $section->lessons;
        });
        
        $standaloneLessons = $roadmap->lessons->whereNull('section_id');
        
        $allLessons = $lessonsFromSections->concat($standaloneLessons)->sortBy('sort_order')->values();

        // Determine current lesson
        if ($lessonId) {
            $currentLesson = $allLessons->firstWhere('id', $lessonId);
        } else {
            $currentLesson = $allLessons->first();
        }

        if (!$currentLesson) {
            return redirect()->route('learning.roadmaps.show', $roadmapId)
                ->with('error', 'Không tìm thấy bài học');
        }

        // Get user progress
        $progressList = $this->roadmapService->getLessonProgressList($userId, $roadmapId);

        // Find next lesson
        $currentIndex = $allLessons->search(function($lesson) use ($currentLesson) {
            return $lesson->id === $currentLesson->id;
        });
        $nextLesson = $allLessons->get($currentIndex + 1);

        // Load user's note for this lesson
        $userNote = \Modules\Learning\Models\LessonNote::where('user_id', $userId)
            ->where('roadmap_lesson_id', $currentLesson->id)
            ->first();

        // Load questions for this lesson
        $lessonQuestions = \Modules\Learning\Models\LessonQuestion::where('roadmap_lesson_id', $currentLesson->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Load user's project submission for this lesson
        $projectSubmission = null;
        if ($currentLesson->project_id) {
            $projectSubmission = \Modules\Learning\Models\ProjectSubmission::where('user_id', $userId)
                ->where('project_id', $currentLesson->project_id)
                ->latest()
                ->first();
        }

        return view('learning::layouts.lesson-view', [
            'roadmap' => $roadmap,
            'currentLesson' => $currentLesson,
            'nextLesson' => $nextLesson,
            'progressList' => $progressList,
            'userNote' => $userNote,
            'lessonQuestions' => $lessonQuestions,
            'projectSubmission' => $projectSubmission,
        ]);
    }

    /**
     * Mark a lesson as completed and redirect to next lesson
     */
    public function completeLesson(Request $request, mixed $roadmapId, mixed $lessonId)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userId = auth()->id();
        
        $result = $this->roadmapService->markLessonCompleted($userId, $roadmapId, $lessonId);

        if ($result) {
            // Find next lesson
            $roadmap = \Modules\Learning\Models\Roadmap::with(['sections.lessons' => function($query) {
                $query->where('is_published', true)->orderBy('sort_order');
            }])->findOrFail($roadmapId);

            $allLessons = $roadmap->sections->flatMap(function($section) {
                return $section->lessons;
            });

            $currentIndex = $allLessons->search(function($lesson) use ($lessonId) {
                return $lesson->id == $lessonId;
            });

            $nextLesson = $allLessons->get($currentIndex + 1);

            if ($nextLesson) {
                return redirect()->route('learning.roadmaps.learn', [$roadmapId, $nextLesson->id])
                    ->with('success', 'Đã hoàn thành bài học!');
            } else {
                return redirect()->route('learning.roadmaps.show', $roadmapId)
                    ->with('success', 'Chúc mừng! Bạn đã hoàn thành lộ trình!');
            }
        }

        return back()->with('error', 'Có lỗi xảy ra');
    }

    /**
     * Save lesson note
     */
    public function saveNote(Request $request, mixed $lessonId)
    {
        if (!auth()->check()) {
            return back()->with('error', 'Vui lòng đăng nhập');
        }

        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $userId = auth()->id();

        \Modules\Learning\Models\LessonNote::updateOrCreate(
            [
                'user_id' => $userId,
                'roadmap_lesson_id' => $lessonId,
            ],
            [
                'content' => $request->input('content'),
            ]
        );

        return back()->with('success', 'Đã lưu ghi chú thành công!');
    }

    /**
     * Post a question for lesson
     */
    public function postQuestion(Request $request, mixed $lessonId)
    {
        if (!auth()->check()) {
            return back()->with('error', 'Vui lòng đăng nhập');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $userId = auth()->id();

        \Modules\Learning\Models\LessonQuestion::create([
            'user_id' => $userId,
            'roadmap_lesson_id' => $lessonId,
            'content' => $request->input('content'),
            'is_answered' => false,
        ]);

        return back()->with('success', 'Đã gửi câu hỏi thành công!');
    }

    /**
     * Submit project for a lesson
     */
    public function submitProject(Request $request, mixed $roadmapId, mixed $lessonId)
    {
        if (!auth()->check()) {
            return back()->with('error', 'Vui lòng đăng nhập');
        }

        $request->validate([
            'github_url' => 'required|url|max:500',
            'live_demo_url' => 'nullable|url|max:500',
            'note' => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:zip,pdf,png,jpg|max:102400',
        ]);

        $userId = auth()->id();
        
        // Get enrollment
        $enrollment = \Modules\Learning\Models\RoadmapEnrollment::where('user_id', $userId)
            ->where('roadmap_id', $roadmapId)
            ->firstOrFail();

        // Get lesson and project
        $lesson = \Modules\Learning\Models\RoadmapLesson::findOrFail($lessonId);
        
        if (!$lesson->project_id) {
            return back()->with('error', 'Bài học này không có dự án');
        }

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('project-submissions', 'public');
        }

        // Create or update submission
        \Modules\Learning\Models\ProjectSubmission::create([
            'project_id' => $lesson->project_id,
            'user_id' => $userId,
            'enrollment_id' => $enrollment->id,
            'github_url' => $request->input('github_url'),
            'live_demo_url' => $request->input('live_demo_url'),
            'attachment_path' => $attachmentPath,
            'note' => $request->input('note'),
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Đã nộp dự án thành công! Đợi giảng viên review.');
    }
}