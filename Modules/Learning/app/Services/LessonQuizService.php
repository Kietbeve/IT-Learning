<?php

namespace Modules\Learning\Services;

use Modules\Learning\Models\LessonQuiz;
use Modules\Learning\Models\LessonQuizQuestion;
use Modules\Learning\Models\LessonQuizAttempt;
use Modules\Learning\Models\LessonQuizAnswer;
use Modules\Learning\Models\RoadmapLesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LessonQuizService
{
    /**
     * Tạo quiz mới cho lesson
     */
    public function createQuiz(int $lessonId, array $data): LessonQuiz
    {
        return DB::transaction(function () use ($lessonId, $data) {
            $quiz = LessonQuiz::create([
                'lesson_id' => $lessonId,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'duration_minutes' => $data['duration_minutes'] ?? null,
                'pass_score' => $data['pass_score'] ?? 60,
                'max_attempts' => $data['max_attempts'] ?? null,
                'shuffle_questions' => $data['shuffle_questions'] ?? false,
                'shuffle_options' => $data['shuffle_options'] ?? false,
                'show_correct_answers' => $data['show_correct_answers'] ?? true,
                'allow_review' => $data['allow_review'] ?? true,
                'is_required' => $data['is_required'] ?? false,
                'is_published' => $data['is_published'] ?? true,
            ]);

            return $quiz;
        });
    }

    /**
     * Cập nhật quiz
     */
    public function updateQuiz(int $quizId, array $data): LessonQuiz
    {
        $quiz = LessonQuiz::findOrFail($quizId);
        
        $quiz->update([
            'title' => $data['title'] ?? $quiz->title,
            'description' => $data['description'] ?? $quiz->description,
            'duration_minutes' => $data['duration_minutes'] ?? $quiz->duration_minutes,
            'pass_score' => $data['pass_score'] ?? $quiz->pass_score,
            'max_attempts' => $data['max_attempts'] ?? $quiz->max_attempts,
            'shuffle_questions' => $data['shuffle_questions'] ?? $quiz->shuffle_questions,
            'shuffle_options' => $data['shuffle_options'] ?? $quiz->shuffle_options,
            'show_correct_answers' => $data['show_correct_answers'] ?? $quiz->show_correct_answers,
            'allow_review' => $data['allow_review'] ?? $quiz->allow_review,
            'is_required' => $data['is_required'] ?? $quiz->is_required,
            'is_published' => $data['is_published'] ?? $quiz->is_published,
        ]);

        return $quiz->fresh();
    }

    /**
     * Xóa quiz
     */
    public function deleteQuiz(int $quizId): bool
    {
        $quiz = LessonQuiz::findOrFail($quizId);
        return $quiz->delete();
    }

    /**
     * Thêm câu hỏi vào quiz
     */
    public function addQuestion(int $quizId, array $data): LessonQuizQuestion
    {
        $quiz = LessonQuiz::findOrFail($quizId);
        
        // Lấy sort_order lớn nhất hiện tại
        $maxOrder = $quiz->questions()->max('sort_order') ?? 0;
        
        return LessonQuizQuestion::create([
            'quiz_id' => $quizId,
            'type' => $data['type'],
            'question_text' => $data['question_text'],
            'options' => $data['options'], // JSON array
            'explanation' => $data['explanation'] ?? null,
            'score' => $data['score'] ?? 1,
            'sort_order' => $data['sort_order'] ?? ($maxOrder + 1),
        ]);
    }

    /**
     * Cập nhật câu hỏi
     */
    public function updateQuestion(int $questionId, array $data): LessonQuizQuestion
    {
        $question = LessonQuizQuestion::findOrFail($questionId);
        
        $question->update([
            'type' => $data['type'] ?? $question->type,
            'question_text' => $data['question_text'] ?? $question->question_text,
            'options' => $data['options'] ?? $question->options,
            'explanation' => $data['explanation'] ?? $question->explanation,
            'score' => $data['score'] ?? $question->score,
            'sort_order' => $data['sort_order'] ?? $question->sort_order,
        ]);

        return $question->fresh();
    }

    /**
     * Xóa câu hỏi
     */
    public function deleteQuestion(int $questionId): bool
    {
        $question = LessonQuizQuestion::findOrFail($questionId);
        return $question->delete();
    }

    /**
     * Sắp xếp lại thứ tự câu hỏi
     */
    public function reorderQuestions(int $quizId, array $questionIds): void
    {
        DB::transaction(function () use ($questionIds) {
            foreach ($questionIds as $index => $questionId) {
                LessonQuizQuestion::where('id', $questionId)
                    ->update(['sort_order' => $index + 1]);
            }
        });
    }

    /**
     * Bắt đầu làm quiz
     */
    public function startAttempt(int $quizId, int $userId): LessonQuizAttempt
    {
        $quiz = LessonQuiz::with('questions')->findOrFail($quizId);
        
        // Kiểm tra user có thể làm quiz không
        if (!$quiz->canUserRetake($userId)) {
            throw new \Exception('Bạn đã hết số lần làm quiz này');
        }
        
        // Lấy số lần đã làm
        $attemptNumber = $quiz->getAttemptsCountByUser($userId) + 1;
        
        return DB::transaction(function () use ($quiz, $userId, $attemptNumber) {
            // Tạo attempt
            $attempt = LessonQuizAttempt::create([
                'quiz_id' => $quiz->id,
                'user_id' => $userId,
                'lesson_id' => $quiz->lesson_id,
                'started_at' => now(),
                'expires_at' => $quiz->duration_minutes ? now()->addMinutes($quiz->duration_minutes) : null,
                'total_questions' => $quiz->total_questions,
                'max_score' => $quiz->max_score,
                'status' => 'in_progress',
                'attempt_number' => $attemptNumber,
            ]);

            // Lấy danh sách câu hỏi
            $questions = $quiz->questions;
            
            // Trộn câu hỏi nếu cấu hình
            if ($quiz->shuffle_questions) {
                $questions = $questions->shuffle();
            }

            // Tạo các câu trả lời rỗng
            foreach ($questions as $question) {
                LessonQuizAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_options' => null,
                    'is_correct' => false,
                    'score' => 0,
                ]);
            }

            return $attempt->load('answers.question');
        });
    }

    /**
     * Submit câu trả lời cho một câu hỏi
     */
    public function submitAnswer(int $attemptId, int $questionId, array $selectedOptions): LessonQuizAnswer
    {
        $attempt = LessonQuizAttempt::findOrFail($attemptId);
        
        // Kiểm tra quiz còn trong thời gian làm không
        if ($attempt->isExpired()) {
            throw new \Exception('Quiz đã hết thời gian');
        }
        
        if (!$attempt->isInProgress()) {
            throw new \Exception('Quiz không ở trạng thái in_progress');
        }
        
        // Tìm answer
        $answer = LessonQuizAnswer::where('attempt_id', $attemptId)
            ->where('question_id', $questionId)
            ->firstOrFail();
        
        // Cập nhật câu trả lời
        $answer->selected_options = $selectedOptions;
        $answer->answered_at = now();
        $answer->save();
        
        // Chấm điểm
        $answer->gradeAnswer();
        
        return $answer->fresh();
    }

    /**
     * N?p b�i quiz
     */
    public function submitAttempt(int $attemptId): LessonQuizAttempt
    {
        $attempt = LessonQuizAttempt::with('quiz', 'answers')->findOrFail($attemptId);
        
        if (!$attempt->isInProgress()) {
            throw new \Exception('Quiz kh�ng ? tr?ng th�i in_progress');
        }
        
        return DB::transaction(function () use ($attempt) {
            // C?p nh?t tr?ng th�i
            $attempt->submitted_at = now();
            $attempt->status = $attempt->isExpired() ? 'auto_submitted' : 'submitted';
            $attempt->save();
            
            // Ch?m di?m to�n b? quiz
            $this->gradeAttempt($attempt->id);
            
            return $attempt->fresh();
        });
    }

    /**
     * Ch?m di?m quiz
     */
    public function gradeAttempt(int $attemptId): void
    {
        $attempt = LessonQuizAttempt::with('answers.question')->findOrFail($attemptId);
        
        DB::transaction(function () use ($attempt) {
            // Ch?m t?ng c�u h?i chua du?c ch?m
            foreach ($attempt->answers as $answer) {
                if (!$answer->isAnswered()) {
                    // C�u kh�ng tr? l?i = 0 di?m
                    $answer->is_correct = false;
                    $answer->score = 0;
                    $answer->save();
                } else {
                    // Ch?m di?m c�u tr? l?i
                    $answer->gradeAnswer();
                }
            }
            
            // T�nh t?ng di?m v� c?p nh?t attempt
            $attempt->calculateScore();
            
            // C?p nh?t tr?ng th�i
            $attempt->status = 'completed';
            $attempt->save();
        });
    }

    /**
     * L?y k?t qu? quiz
     */
    public function getAttemptResults(int $attemptId): LessonQuizAttempt
    {
        return LessonQuizAttempt::with([
            'quiz',
            'user',
            'lesson',
            'answers.question'
        ])->findOrFail($attemptId);
    }

    /**
     * L?y danh s�ch c�c l?n l�m quiz c?a user
     */
    public function getUserAttempts(int $quizId, int $userId)
    {
        return LessonQuizAttempt::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->with('quiz')
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
