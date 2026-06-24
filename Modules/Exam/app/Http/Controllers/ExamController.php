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

        // Goi danh sach bai kiem tra tu Sevice
        $exams = $this->examService->search([
        'keyword' => $request->keyword
        ]);

        // Truyen du lieu vao view
        return view('exam::index',compact('exams','categories'));
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('exam::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('exam::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('exam::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
    //view contributor/questions
    public function questionManager()
    {
        return view("exam::contributor.question-table");
    }
    public function examManager()
    {
        $stats = Exam::query()
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

    public function startExam(Request $request,$examSlug){
        $exam =$this->examService->getExamBySlug($examSlug);

        $attempt = $this->examService->startExam(
            exam: $exam,
            user: auth()->user(),
            sessionId: $request->cookie("exam_{$exam->id}")
        );

        return redirect()
            ->route('exam.attempt.take', $attempt->session_id)
            ->cookie(//cookie dành cho người dùng không đăng nhập lưu phiên làm bài
                "exam_{$exam->id}",
                $attempt->session_id,
                60 * 24 // 1 ngày
            );
    }

    /**
     * Finalize attempt grading - recalculate scores and mark as submitted
     */
    public function finalizeAttempt($attemptId)
    {
        try {
            // Call service to finalize grading
            $stats = $this->examService->finalizeAttemptGrading($attemptId);

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
