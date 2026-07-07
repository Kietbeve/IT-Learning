<?php

namespace Modules\Exam\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Exam\Models\Exam;
use Modules\Exam\Services\ExamService;
use Modules\Exam\Models\AttemptAnswer;
use Modules\Exam\Models\ExamAttempt;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Exam\Exports\QuestionTemplateExport;
use Modules\Exam\Jobs\SendAttemptResultEmailJob;
class ExamController extends Controller
{
    /*
     * Khoi tao ExamControler
     */
    public function __construct(
      private ExamService $examService
    )
    {
      // throw new \Exception('Not implemented');
    }

    /*
     * Trang danh sách bài thi của exam
     */
    public function index(Request $request)
    {
        // Lấy danh sách tất cả danh mục
        $categories = $this->examService->getAllCategories();

        //lấy danh sách tag
        $tags = $this->examService->getAllTags();

        // Gọi danh sách bài kiểm tra từ Service
        $exams = $this->examService->search([
            'keyword'  => $request->keyword,
            'category' => $request->category,
            'type'     => $request->type,
            'sort'     => $request->sort,
            'tags'     => $request->tags,
        ]);

        // Truyen du lieu vao view
        return view('exam::index',compact('exams','categories','tags'));
    }

    /**
     * Hàm render exam_detail
     */
    public function showExamDetail($examSlug)
    {
      $exam =$this->examService->getExamBySlug($examSlug);
      return view('exam::exam_detail',compact('exam'));
    }

    /**
     * Hàm dùng để test giao diện trang bai thi
     */
    public function examAttempt()
    {
        return view('exam::exam_attempt');
    }

    /**
     * Hàm dùng để test giao diện trang kết quả bài thi
     */
    public function examResult()
    {
        return view('exam::exam_result');
    }

    //view contributor/questions
    public function questionManager()
    {
        return view("exam::contributor.question-table");
    }
    public function examManager()
    {
        $stats = Exam::query()
        ->where('author_id',auth()->id())
        ->selectRaw('COUNT(*) as total')
        ->selectRaw("SUM(status = 'approved') as approved")
        ->selectRaw("SUM(status = 'pending') as pending")
        ->selectRaw("SUM(status = 'rejected') as rejected")
        // ->selectRaw("SUM(status = 'published') as published")
        ->selectRaw("SUM(status = 'draft') as draft")
        ->first();

        $stats->no_questions = Exam::doesntHave('questions')->count();

        return view("exam::contributor.exam-table", compact('stats'));
    }
    public function examDetail($examId)
    {
        $exam = Exam::findOrFail($examId);
        $questionCount = $exam->questions()->count();
        $attemptCount = $exam->attempts()->count();
        //return view ExamDetail with data
        return view('exam::contributor.exam-detail', 
            compact('exam','questionCount','attemptCount'));
    }
    public function examQuestionManager($examId)
    {
        $exam = Exam::findOrFail($examId);

        $questions = $exam->questions();

        $stats = [
            'total_questions' => (clone $questions)->count(),

            'multiple_choice' => (clone $questions)
                ->where('type', 'multiple_choice')
                ->count(),
            
            'single_choice' => (clone $questions)
            ->where('type', 'single_choice')
            ->count(), 

            'essay' => (clone $questions)
                ->where('type', 'essay')
                ->count(),

            'total_score' => (clone $questions)
                ->sum('exam_questions.score'),
        ];
        return view("exam::contributor.exam-question-table", compact('exam', 'stats'));
    }

    public function examAttemptManager($examId)
    {
        $exam = Exam::findOrFail($examId);

        $attempts = $exam->attempts();

        $stats = [
            'total_attempts' => (clone $attempts)->count(),

            'submitted_attempts' => (clone $attempts)
                ->where('status', 'submitted')
                ->count(),
            
            'pass_rate' => (clone $attempts)
            ->where('status', 'pass')
            ->count(), 

            'average_score' => (clone $attempts)
                ->avg('score'),
        ];
        return view("exam::contributor.exam-attempt-table", compact('exam', 'stats'));
    }
    public function attemptAnswerDetail($attemptId)
    {
    $examAttempt = ExamAttempt::query()
        ->with([
            'exam',
            'user',
            'answers.question',
        ])
        ->findOrFail($attemptId);

        return view(
            'exam::contributor.attempt-answer-table',
            compact('examAttempt')
        );
    }

    public function examReviewTable()
    {
        return view("exam::admin.exam-review-table");
    }

    // Hàm xử lý bắt đầu làm bài thi
    public function startExam(Request $request,$examSlug){
        $exam =$this->examService->getExamBySlug($examSlug);

        //lấy cookie guest_exam_attempts
        $attempts = json_decode($request->cookie('guest_exam_attempts', '{}'),true);
        $attempts = is_array($attempts) ? $attempts : [];

        // lấy danh sách session_id ứng với bài thi
        $sessionIds = $attempts[$exam->id] ?? [];

        $attempt = $this->examService->startExam(
            exam: $exam,
            user: auth()->user(),
            sessionIds: $sessionIds,
        );

        //ko thêm nếu đã đăng nhập
        if (! auth()->check()) {
            // Không thêm nếu đã tồn tại
            $attempts[$exam->id] ??= [];

            if (! in_array($attempt->session_id, $attempts[$exam->id], true)) {
                $attempts[$exam->id][] = $attempt->session_id;
            }
            // thêm cookie dành cho người dùng không đăng nhập
            return redirect()
            ->route('exam.attempt.take', $attempt->session_id)
            ->cookie(
                'guest_exam_attempts',
                json_encode($attempts),
                60 * 24 * 30
            );
        }

        // nếu đã đăng nhập thì chuyển hướng thẳng
        return redirect()->route('exam.attempt.take', $attempt->session_id);
    }

    /**
     * Finalize attempt grading - recalculate scores and mark as submitted
     */
    public function finalizeAttempt($attemptId)
    {
        try {
            // Call service to finalize grading
            $stats = $this->examService->finalizeAttemptGrading($attemptId);

            // Đẩy vào Queue để gửi email đến người làm
            SendAttemptResultEmailJob::dispatch($attemptId, $stats);

            // Redirect back with success message
            return redirect()
                ->back()
                ->with('success', sprintf(
                    'Chốt kết quả thành công! Điểm: %.1f/%.1f (%.1f%%) • Đúng: %d • Sai: %d • Bỏ qua: %d',
                    $stats['score'],
                    $stats['max_score'],
                    $stats['percent_score'],
                    $stats['correct_answers'],
                    $stats['wrong_answers'],
                    $stats['skipped_answers']
                ));

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Không thể chốt kết quả: ' . $e->getMessage());
        }
    }

    public function template()
    {
        return Excel::download(
            new QuestionTemplateExport(),
            'question-template.xlsx'
        );
    }
}
