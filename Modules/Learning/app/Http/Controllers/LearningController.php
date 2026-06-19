<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Learning\Services\RoadmapService;
use Modules\Learning\Models\RoadmapLesson;

class LearningController extends Controller
{
    protected $roadmapService;

    // Tiêm Service xử lý nghiệp vụ vào qua Constructor
    public function __construct(RoadmapService $roadmapService)
    {
        $this->roadmapService = $roadmapService;
    }

    /**
     * 1. TRANG DANH SÁCH ROADMAP (Có bộ lọc chuẩn xác từ Service)
     */
    public function index(Request $request)
    {
        // Lấy dữ liệu lọc từ request và đưa vào tầng dịch vụ xử lý
        $roadmaps = $this->roadmapService->getFilteredRoadmaps($request->all(), 6);

        return view('learning::layouts.roadmap-list', compact('roadmaps'));
    }

    /**
     * 2. TRANG CHI TIẾT LỘ TRÌNH (Khối hộp quản trị trung tâm)
     */
    public function show($id)
    {
        // Nhận dữ liệu sạch (lộ trình, tiến độ học viên, mục tiêu ngắn gọn) từ Service
        $data = $this->roadmapService->getRoadmapDetails($id);

        return view('learning::layouts.roadmap-detail', $data);
    }

    /**
     * 3. XỬ LÝ ĐĂNG KÝ / THAM GIA LỘ TRÌNH
     */
    public function enroll($id)
    {
        $enrollment = $this->roadmapService->enrollUser($id);

        if ($enrollment) {
            return redirect()->route('learning.roadmaps.learn', $id)
                ->with('success', 'Chào mừng bạn gia nhập lộ trình học tập chuyên sâu!');
        }

        return back()->with('error', 'Có lỗi xảy ra khi kích hoạt không gian học.');
    }

    /**
     * 4. KHÔNG GIAN HỌC TẬP CHUYÊN SÂU (WORKSPACE CHIA ĐÔI MÀN HÌNH)
     */
    public function learn($roadmapId, $lessonId = null)
    {
        $data = $this->roadmapService->getRoadmapDetails($roadmapId);
        $roadmap = $data['roadmap'];
        $enrollment = $data['enrollment'];
        $completedLessonIds = $data['completedLessonIds'];

        if (!$enrollment) {
            return redirect()->route('learning.roadmaps.show', $roadmapId)
                ->with('error', 'Vui lòng nhấn nút [Tham gia lộ trình] trước khi truy cập không gian học tập.');
        }

        // Nếu người dùng không truyền lessonId cụ thể, tự động tìm bài học chưa làm gần nhất để học tiếp
        if (!$lessonId) {
            $currentLesson = null;
            foreach ($roadmap->sections as $section) {
                foreach ($section->lessons as $lesson) {
                    if (!in_array($lesson->id, $completedLessonIds)) {
                        $currentLesson = $lesson;
                        break 2;
                    }
                }
            }

            // Nếu học xong hết rồi hoặc chưa có bài mới, lấy mặc định bài đầu tiên
            if (!$currentLesson && isset($roadmap->sections[0]->lessons[0])) {
                $currentLesson = $roadmap->sections[0]->lessons[0];
            }

            if (!$currentLesson) {
                return redirect()->route('learning.roadmaps.show', $roadmapId)
                    ->with('error', 'Lộ trình này đang được hoàn thiện nội dung.');
            }

            return redirect()->route('learning.roadmaps.learn', [$roadmapId, $currentLesson->id]);
        }

        $currentLesson = RoadmapLesson::where('is_published', true)->findOrFail($lessonId);

        return view('learning::lesson-view', compact('roadmap', 'enrollment', 'completedLessonIds', 'currentLesson'));
    }

    /**
     * 5. ĐÁNH DẤU HOÀN THÀNH BÀI HỌC & ĐIỀU HƯỚNG THÔNG MINH THEO YÊU CẦU
     */
    public function completeLesson($roadmapId, $lessonId)
    {
        // Gọi Service xử lý lưu tiến độ và trả về bài học tiếp theo (nếu có)
        $nextLesson = $this->roadmapService->completeLesson($roadmapId, $lessonId);

        // KỊCH BẢN A: Nếu còn bài học kế tiếp trong lộ trình -> Chuyển hướng sang bài tiếp theo ngay lập tức
        if ($nextLesson) {
            return redirect()->route('learning.roadmaps.learn', [$roadmapId, $nextLesson->id])
                ->with('success', 'Tuyệt vời! Hệ thống đã mở khóa chặng tiếp theo cho bạn.');
        }

        // KỊCH BẢN B: Nếu không còn bài học nào (Học viên xuất sắc hoàn thành bài cuối cùng)
        // Đẩy về trang chi tiết lộ trình kèm thông báo vinh danh hoành tráng
        return redirect()->route('learning.roadmaps.show', $roadmapId)
            ->with('celebrate', 'Xuất sắc! Bạn đã vượt qua toàn bộ thử thách và hoàn thành trọn vẹn lộ trình học tập này! 🎉👑');
    }

    /**
     * 6. LƯU SỔ TAY GHI CHÚ (Tác vụ bổ trợ học tập)
     */
    public function saveNote(Request $request, $lessonId)
    {
        $request->validate(['content' => 'required|string|min:5']);
        
        $this->roadmapService->saveNote($lessonId, $request->content);

        return back()->with('success', 'Nội dung kỹ thuật đã được lưu lại vào Sổ tay cá nhân.');
    }

    /**
     * 7. GỬI CÂU HỎI THẢO LUẬN / DEBUG LỖI KỸ THUẬT
     */
    public function postQuestion(Request $request, $lessonId)
    {
        $request->validate(['content' => 'required|string|min:10']);

        $this->roadmapService->saveQuestion($lessonId, $request->content);

        return back()->with('success', 'Yêu cầu trợ giúp đã được gửi lên hệ thống.');
    }
}