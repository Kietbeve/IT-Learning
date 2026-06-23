<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Modules\Learning\Services\RoadmapService; 
use Modules\Learning\Models\Roadmap;
use Illuminate\Support\Facades\Auth;
use Modules\Learning\Models\LessonQuestion; // SỬA: Đổi từ Question thành LessonQuestion cho đúng model dự án
use App\Models\User;
use Modules\Learning\Http\Requests\StoreProjectSubmissionRequest;
use Modules\Learning\Services\ProjectSubmissionService;

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
        $roadmaps = $this->roadmapService->getFilteredRoadmaps($request->all(), 6);
        
        /** @var mixed $user */
        $user = Auth::user();
        $registeredRoadmaps = collect();
        $unregisteredRoadmaps = Roadmap::all();

        if ($user) {
            // Lấy danh sách ID lộ trình mà user này thực sự đã đăng ký từ bảng roadmap_enrollments
            $registeredIds = \Modules\Learning\Models\RoadmapEnrollment::where('user_id', $user->id)
                ->pluck('roadmap_id')
                ->toArray();

            // Truy vấn lấy dữ liệu roadmap tương ứng để hiển thị lên Sidebar
            $registeredRoadmaps = Roadmap::whereIn('id', $registeredIds)->get();
            $unregisteredRoadmaps = Roadmap::whereNotIn('id', $registeredIds)->get();
        }

        return view('learning::layouts.roadmap-list', compact(
            'roadmaps', 
            'registeredRoadmaps', 
            'unregisteredRoadmaps'
        ));
    }

    /**
     * TRANG 2: Chi tiết lộ trình
     */
    public function show(mixed $id): View
    {
        $userId = Auth::id();
        
        /** @var mixed $user */
        $user = Auth::user();
        
        $registeredRoadmaps = collect();
        $unregisteredRoadmaps = Roadmap::all();

        if ($user) {
            // Đồng bộ cách lấy dữ liệu giống trang index từ bảng roadmap_enrollments
            $registeredIds = \Modules\Learning\Models\RoadmapEnrollment::where('user_id', $user->id)
                ->pluck('roadmap_id')
                ->toArray();

            $registeredRoadmaps = Roadmap::whereIn('id', $registeredIds)->get();
            $unregisteredRoadmaps = Roadmap::whereNotIn('id', $registeredIds)->get();
        }

        return view('learning::layouts.roadmap-detail', array_merge(
            [
                'id' => $id, 
                'registeredRoadmaps' => $registeredRoadmaps, 
                'unregisteredRoadmaps' => $unregisteredRoadmaps
            ],
            $this->roadmapService->getRoadmapDetail($id, $userId)
        ));
    }

    /**
     * TRANG 3: Nội dung chi tiết bài học
     */
    public function showLesson(mixed $roadmapId, mixed $lessonId): View
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để học');
        }

        $userId = Auth::id();

        // Check enrollment - Học viên phải đăng ký lộ trình trước khi xem chi tiết bài học
        if (!$this->roadmapService->checkEnrollment($userId, $roadmapId)) {
            return redirect()->route('learning.roadmaps.show', $roadmapId)
                ->with('error', 'Bạn cần đăng ký lộ trình này trước khi xem chi tiết bài học');
        }

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
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để đăng ký lộ trình');
        }

        $userId = Auth::id();
        
        // Check if already enrolled
        if ($this->roadmapService->checkEnrollment($userId, $id)) {
            return back()->with('info', 'Bạn đã đăng ký lộ trình này rồi');
        }

        // Enroll user
        $enrollment = $this->roadmapService->enrollRoadmap($userId, $id);

        if ($enrollment) {
           return redirect()->route('learning.roadmaps.show', $id)->with('success', 'Đăng ký lộ trình thành công!');
        }

        return back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại');
    }

    /**
     * TRANG 4: Không gian học tập - Learning workspace
     */
    public function learn(mixed $roadmapId, mixed $lessonId = null)
    {
        // Check authentication
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để học');
        }

        $userId = Auth::id();

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

        $pdfFile = '';
        if ($roadmapId == 1) {
            $pdfMap = [
                1 => 'Bai_1_HTML5_CSS3.pdf',
                5 => 'Bai_2_Tailwind_CSS.pdf',
                3 => 'Routes_va_Controllers_chi_tiet.pdf',
                4 => 'Bai_4_ReactJS.pdf',
                23 => 'Lo_Trinh_va_Tong_Ket_Backend_Developer.pdf',
                25 => 'Lo_Trinh_va_Tong_Ket_Backend_Developer.pdf',
                //5 => 'Bai_5_Deploy.pdf',
            ];
            // Sử dụng ID của bài học đang xem để map ra file PDF
            $pdfFile = $pdfMap[$currentLesson->id] ?? '';
        }

        if ($roadmapId == 2) {
            $pdfMap = [
                1 => 'Bai_1_HTML5_CSS3.pdf',
                5 => 'CSS_Flexbox_va_Grid.pdf',
                7 => 'ES6_Modern_JavaScript.pdf',
                4 => 'Bai_4_ReactJS.pdf',
                19 => 'Tai_Lieu_Ly_Thuyet_Lo_Trinh_Frontend_Developer.pdf',
                21 => 'Tong_Ket_Lo_Trinh_Frontend_Developer.pdf',
                //5 => 'Bai_5_Deploy.pdf',
            ];
            // Sử dụng ID của bài học đang xem để map ra file PDF
            $pdfFile = $pdfMap[$currentLesson->id] ?? '';
        }

        if ($roadmapId == 3) {
            $pdfMap = [
                8 => 'Kien_Truc_he_thong_va_Lo_Trinh_Fullstack_Developer.pdf',
                27 => 'Kien_Truc_he_thong_va_Lo_Trinh_Fullstack_Developer.pdf',
                29 => 'Kien_Truc_he_thong_va_Lo_Trinh_Fullstack_Developer.pdf',
                4 => 'Bai_4_ReactJS.pdf',
                19 => 'Tai_Lieu_Ly_Thuyet_Lo_Trinh_Frontend_Developer.pdf',
                21 => 'Tong_Ket_Lo_Trinh_Frontend_Developer.pdf',
                //5 => 'Bai_5_Deploy.pdf',
            ];
            // Sử dụng ID của bài học đang xem để map ra file PDF
            $pdfFile = $pdfMap[$currentLesson->id] ?? '';
        }

        if ($roadmapId == 4) {
            $pdfMap = [
                31 => 'Tai_Lieu_Ly_Thuyet_Lo_Trinh_Mobile_Development.pdf',
                33 => 'Tong_Ket_Lo_Trinh_Mobile_Development.pdf',
                
            ];
            // Sử dụng ID của bài học đang xem để map ra file PDF
            $pdfFile = $pdfMap[$currentLesson->id] ?? '';
        }

        if ($roadmapId == 5) {
            $pdfMap = [
                10 => 'Tong_Ket_Lo_Trinh_Linux_va_Shell_Scripting.pdf',
                35 => 'Tai_Lieu_Ly_Thuyet_Lo_Trinh_DevOps_Engineer.pdf',
                37 => 'Tong_Ket_Lo_Trinh_DevOps_Engineer.pdf',
                
            ];
            // Sử dụng ID của bài học đang xem để map ra file PDF
            $pdfFile = $pdfMap[$currentLesson->id] ?? '';
        }

         if ($roadmapId == 6) {
            $pdfMap = [
               
                39 => 'lo_trinh_data_science_ai.pdf',
                41 => 'lo_trinh_data_science_ai.pdf',
                
            ];
            // Sử dụng ID của bài học đang xem để map ra file PDF
            $pdfFile = $pdfMap[$currentLesson->id] ?? '';
        }

         if ($roadmapId == 7) {
            $pdfMap = [
               
                13 => 'co_ban_an_ninh_mang.pdf',
                43 => 'lo_trinh_cybersecurity.pdf',
                45 => 'lo_trinh_cybersecurity.pdf',
                
            ];
            // Sử dụng ID của bài học đang xem để map ra file PDF
            $pdfFile = $pdfMap[$currentLesson->id] ?? '';
        }

         if ($roadmapId == 8) {
            $pdfMap = [
               
               
                47 => 'lo_trinh_cloud_computing.pdf',
                49 => 'lo_trinh_cloud_computing.pdf',
                
            ];
            // Sử dụng ID của bài học đang xem để map ra file PDF
            $pdfFile = $pdfMap[$currentLesson->id] ?? '';
        }



        // Truyền thêm biến 'pdfFile' sang giao diện
        return view('learning::layouts.lesson-view', [
            'roadmap' => $roadmap,
            'currentLesson' => $currentLesson,
            'nextLesson' => $nextLesson,
            'progressList' => $progressList,
            'userNote' => $userNote,
            'lessonQuestions' => $lessonQuestions,
            'projectSubmission' => $projectSubmission,
            'pdfFile' => $pdfFile,
        ]);
    }

    /**
     * Mark a lesson as completed and redirect to next lesson
     */
    public function completeLesson(Request $request, mixed $roadmapId, mixed $lessonId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        
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
        if (!Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập');
        }

        $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $userId = Auth::id();

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
        if (!Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập');
        }

        $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $userId = Auth::id();

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
    public function submitProject(StoreProjectSubmissionRequest $request, mixed $roadmapId, mixed $lessonId)
    {
        if (!Auth::check()) {
            return back()->with('error', 'Vui lòng đăng nhập');
        }

        $userId = Auth::id();
        $submissionService = app(ProjectSubmissionService::class);

        try {
            $submission = $submissionService->submitProject(
                userId: $userId,
                lessonId: (int) $lessonId,
                roadmapId: (int) $roadmapId,
                githubUrl: $request->input('github_url'),
                liveDemoUrl: $request->input('live_demo_url'),
                note: $request->input('note'),
                attachment: $request->file('attachment')
            );

            $message = $submission->submission_no > 1 
                ? "Đã nộp lại dự án thành công (lần {$submission->submission_no})! Đợi giảng viên review."
                : 'Đã nộp dự án thành công! Đợi giảng viên review.';

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Xóa bình luận / câu hỏi thảo luận của bài học
     */
    public function destroyQuestion(int $question_id)
    {
        // SỬA CHÍNH XÁC: Gọi đúng Model LessonQuestion của hệ thống
        $question = LessonQuestion::findOrFail($question_id);

        // BẢO MẬT: Chỉ cho phép chính chủ nhân của bình luận đó mới được phép xóa
        if (Auth::id() !== $question->user_id) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa bình luận này!');
        }

        // Tiến hành xóa khỏi Cơ sở dữ liệu
        $question->delete();

        // Quay lại trang không gian học tập và gửi kèm thông báo thành công
        return redirect()->back()->with('success', 'Đã xóa bình luận thành công.');
    }
    
}