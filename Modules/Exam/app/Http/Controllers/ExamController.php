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
    public function index()
    {
        // Goi du lieu tu Sevice
        $exams = $this->examService->getExamList();

        // Truyen du lieu vao view
        return view('exam::index',compact('exams'));
    }

    /**
     * Hàm dùng để test giao diện trang chi tiết bài thi
     */
    public function viewDetail()
    {
        return view('exam::detail');
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
        return view("exam::contributor.exam-table");
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
}
