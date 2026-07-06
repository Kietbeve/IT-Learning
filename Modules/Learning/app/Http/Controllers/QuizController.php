<?php

namespace Modules\Learning\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Learning\Services\LessonQuizService;
use Modules\Learning\Models\LessonQuiz;
use Modules\Learning\Models\LessonQuizAttempt;

class QuizController extends Controller
{
    protected $quizService;

    public function __construct(LessonQuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    /**
     * Bắt đầu làm quiz
     */
    public function startQuiz(Request $request, $lessonId)
    {
        try {
            $userId = auth()->id();
            
            // Lấy quiz của lesson
            $quiz = LessonQuiz::where('lesson_id', $lessonId)
                ->where('is_published', true)
                ->firstOrFail();
            
            // Kiểm tra user có thể làm quiz không
            if (!$quiz->canUserRetake($userId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã hết số lần làm quiz này'
                ], 403);
            }
            
            // Tạo attempt mới
            $attempt = $this->quizService->startAttempt($quiz->id, $userId);
            
            return response()->json([
                'success' => true,
                'message' => 'Bắt đầu quiz thành công',
                'data' => [
                    'attempt_id' => $attempt->id,
                    'expires_at' => $attempt->expires_at,
                    'questions' => $attempt->answers->map(function ($answer) {
                        return [
                            'id' => $answer->question->id,
                            'type' => $answer->question->type,
                            'question_text' => $answer->question->question_text,
                            'options' => $answer->question->options,
                            'score' => $answer->question->score,
                        ];
                    })
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit câu trả lời cho một câu hỏi
     */
    public function submitQuizAnswer(Request $request, $attemptId)
    {
        $request->validate([
            'question_id' => 'required|exists:lesson_quiz_questions,id',
            'selected_options' => 'required|array'
        ]);

        try {
            $answer = $this->quizService->submitAnswer(
                $attemptId,
                $request->question_id,
                $request->selected_options
            );

            return response()->json([
                'success' => true,
                'message' => 'Đã lưu câu trả lời',
                'data' => [
                    'answer_id' => $answer->id,
                    'is_correct' => $answer->is_correct,
                    'score' => $answer->score
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Nộp bài quiz
     */
    public function submitQuizAttempt(Request $request, $attemptId)
    {
        try {
            $attempt = $this->quizService->submitAttempt($attemptId);

            return response()->json([
                'success' => true,
                'message' => 'Đã nộp bài quiz thành công',
                'data' => [
                    'attempt_id' => $attempt->id,
                    'score' => $attempt->score,
                    'max_score' => $attempt->max_score,
                    'percent_score' => $attempt->percent_score,
                    'is_passed' => $attempt->is_passed
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xem kết quả quiz
     */
    public function showQuizResult($attemptId)
    {
        try {
            $attempt = $this->quizService->getAttemptResults($attemptId);

            // Kiểm tra quyền xem kết quả
            if ($attempt->user_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem kết quả này'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'attempt' => [
                        'id' => $attempt->id,
                        'started_at' => $attempt->started_at,
                        'submitted_at' => $attempt->submitted_at,
                        'score' => $attempt->score,
                        'max_score' => $attempt->max_score,
                        'percent_score' => $attempt->percent_score,
                        'is_passed' => $attempt->is_passed,
                        'total_questions' => $attempt->total_questions,
                        'correct_answers' => $attempt->correct_answers,
                        'wrong_answers' => $attempt->wrong_answers,
                    ],
                    'quiz' => [
                        'title' => $attempt->quiz->title,
                        'show_correct_answers' => $attempt->quiz->show_correct_answers,
                    ],
                    'answers' => $attempt->answers->map(function ($answer) use ($attempt) {
                        return [
                            'question_id' => $answer->question->id,
                            'question_text' => $answer->question->question_text,
                            'type' => $answer->question->type,
                            'options' => $answer->question->options,
                            'selected_options' => $answer->selected_options,
                            'is_correct' => $answer->is_correct,
                            'score' => $answer->score,
                            'explanation' => $attempt->quiz->show_correct_answers ? $answer->question->explanation : null,
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
