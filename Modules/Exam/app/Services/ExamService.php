<?php

namespace Modules\Exam\Services;

use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;


use Modules\Exam\Models\Question;
use Modules\Exam\Models\QuestionOption;
use Modules\Exam\Models\ExamAttempt;
use Modules\Exam\Models\AttemptAnswer;
use Modules\Exam\Models\Exam;
use Modules\Auth\Models\User;


class ExamService
{
    public function __construct(
        //Inject Model vào constructor
        protected Question $questionModel,
        protected QuestionOption $question_optionModel
    ){}
    public function createQuestion(array $data, int $authorId): Question
    {
        return DB::transaction(function () use ($data, $authorId) {

            $question = $this->questionModel->create([
                'author_id' => $authorId,
                'category_id' => $data['category_id'],
                'content' => $data['content'],
                'explanation' => $data['explanation'] ?? null,
                'difficulty' => $data['difficulty'],
                'status' => 'pending',
                'type' => $data['type'],
            ]);

            if (
                in_array(
                    $data['type'],
                    ['single_choice', 'multiple_choice'],
                    true
                )
            ) {
                collect($data['options'] ?? [])
                    ->filter(fn ($option) => filled($option['content'] ?? null))
                    ->values()
                    ->each(function (array $option, int $index) use ($question) {

                        $this->question_optionModel->create([
                            'question_id' => $question->id,
                            'option_key' => chr(65 + $index),
                            'content' => $option['content'],
                            'is_correct' => (bool) ($option['is_correct'] ?? false),
                            'sort_order' => $index + 1,
                        ]);
                    });
            }

            return $question;
        });
    }

    public function updateQuestion(Question $question, array $data): Question
    {
        return DB::transaction(function () use ($question, $data) {

            $question->update([
                'category_id' => $data['category_id'],
                'content' => $data['content'],
                'explanation' => $data['explanation'] ?? null,
                'difficulty' => $data['difficulty'],
                'type' => $data['type'],
            ]);

            if (
                in_array(
                    $data['type'],
                    ['single_choice', 'multiple_choice'],
                    true
                )
            ) {
                // Delete existing options
                $question->options()->delete();

                // Create new options
                collect($data['options'] ?? [])
                    ->filter(fn ($option) => filled($option['content'] ?? null))
                    ->values()
                    ->each(function (array $option, int $index) use ($question) {

                        $this->question_optionModel->create([
                            'question_id' => $question->id,
                            'option_key' => chr(65 + $index),
                            'content' => $option['content'],
                            'is_correct' => (bool) ($option['is_correct'] ?? false),
                            'sort_order' => $index + 1,
                        ]);
                    });
            } else {
                // If changed to essay type, delete all options
                $question->options()->delete();
            }

            return $question->fresh(['options']);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Exam Attempt Methods
    |--------------------------------------------------------------------------
    */

    public function validateAttemptAccess(ExamAttempt $attempt, User $user): void
    {
        // // Check ownership
        // if ($attempt->user_id !== $user->id) {
        //     abort(403, 'Bạn không có quyền truy cập bài thi này');
        // }

        // Check if already submitted
        // if ($attempt->status === 'submitted') {
        //     throw new \Exception('Bài thi đã được nộp. Bạn không thể chỉnh sửa.');
        // }

        // Check expiration
        if ($attempt->isExpired()) {
            $attempt->update(['status' => 'expired']);
            throw new \Exception('Bài thi đã hết hạn.');
        }
    }

    public function checkAttemptExpiration(ExamAttempt $attempt): bool
    {
        return $attempt->isExpired();
    }

    public function loadQuestionsForAttempt(ExamAttempt $attempt): Collection
    {
        $exam = $attempt->exam;
        
        // Check if question order was persisted (for consistency on refresh)
        $persistedOrder = $this->getPersistedQuestionOrder($attempt);
        
        if ($persistedOrder) {
            // Load questions in persisted order
            return Question::whereIn('id', $persistedOrder)
                ->with('options')
                ->get()
                ->sortBy(function ($question) use ($persistedOrder) {
                    return array_search($question->id, $persistedOrder);
                })
                ->values();
        }
        
        // Load fresh questions
        $questions = $exam->questions()
            ->with('options')
            ->orderBy('exam_questions.sort_order')
            ->get();
        
        // Shuffle if official exam type
        if ($exam->type === 'official') {
            $questions = $questions->shuffle();
        }
        
        // Persist order for consistency
        $this->persistQuestionOrder(
            $attempt, 
            $questions->pluck('id')->toArray()
        );
        
        return $questions;
    }

    public function getShuffledQuestions(Exam $exam): Collection
    {
        return $exam->questions()
            ->with('options')
            ->get()
            ->shuffle();
    }

    public function persistQuestionOrder(ExamAttempt $attempt, array $questionIds): void
    {
        // Store in cache for 24 hours (longer than any exam duration)
        Cache::put(
            "attempt_{$attempt->id}_question_order",
            $questionIds,
            now()->addHours(24)
        );
    }

    public function getPersistedQuestionOrder(ExamAttempt $attempt): ?array
    {
        return Cache::get("attempt_{$attempt->id}_question_order");
    }

    public function saveAttemptAnswer(ExamAttempt $attempt, int $questionId, $answer): AttemptAnswer
    {
        // Determine if answer is array (choice questions) or text (essay)
        $data = [
            'attempt_id' => $attempt->id,
            'question_id' => $questionId,
            'answered_at' => now(),
        ];

        if (is_array($answer)) {
            // Single or multiple choice
            $data['selected_option_ids'] = $answer;
            $data['answer_text'] = null;
        } else {
            // Essay question
            $data['selected_option_ids'] = null;
            $data['answer_text'] = $answer;
        }

        return AttemptAnswer::updateOrCreate(
            [
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
            ],
            $data
        );
    }

    public function loadExistingAnswers(ExamAttempt $attempt): Collection
    {
        return $attempt->answers()
            ->get()
            ->keyBy('question_id');
    }

    public function submitAttempt(ExamAttempt $attempt): void
    {
        // Step 1: Get all question IDs from this exam
        $allQuestionIds = $attempt->exam
            ->questions()
            ->pluck('questions.id')
            ->toArray();
        
        // Step 2: Get question IDs that already have answers
        $answeredQuestionIds = $attempt->answers()
            ->pluck('question_id')
            ->toArray();
        
        // Step 3: Find unanswered questions
        $unansweredQuestionIds = array_diff($allQuestionIds, $answeredQuestionIds);
        
        // Step 4: Create empty answer records for unanswered questions
        foreach ($unansweredQuestionIds as $questionId) {
            AttemptAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'selected_option_ids' => null,
                'answer_text' => null,
                'is_correct' => null,
                'answered_at' => null,
            ]);
        }
        
        // Step 5: Update attempt status
        $attempt->update([
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);
    }
    
    //Hàm lấy danh sách bài kiểm tra
    public function getExamList(?int $limit = null)
    {
        $query = Exam::query()
            ->select([
                'id',
                'title',
                'short_description',
                'duration_minutes',
                'author_id',
                'category_id',
                'created_at',
            ])
            ->with([
                'author:id,name',
                'category:id,name',
            ])
            ->withCount('questions')
            ->latest(); // orderByDesc('created_at')

        if ($limit !== null) {
            $query->limit($limit);
        }

        return $query->get();
    }
    /*
    Ket qua tra ve co dang: 
    [
      Modules\Exam\Models\Exam {#8983
        id: 1,
        title: "Đề thi Laravel cơ bản",
        short_description: "Đề thi demo",
        duration_minutes: 30,
        author_id: 1,
        category_id: 1,
        questions_count: 3,
        author: Modules\Auth\Models\User {#9016
          id: 1,
          name: "admin",
        },
        category: App\Models\Category {#9019
          id: 1,
          name: "PHP",
        },
      },
    ],...
    */
}
