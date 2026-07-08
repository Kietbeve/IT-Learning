<?php

namespace Modules\Exam\Services;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Exam\Imports\QuestionsImport;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;

use Modules\Exam\Models\Question;
use Modules\Exam\Models\QuestionOption;
use Modules\Exam\Models\ExamAttempt;
use Modules\Exam\Models\AttemptAnswer;
use Modules\Exam\Models\Exam;
use Modules\Auth\Models\User;
use Modules\Exam\Jobs\GradeExamAttemptJob;
use App\Models\Category;
use App\Models\Tag;
use Modules\Exam\Jobs\SendExamReviewEmailJob;

class ExamService
{
    public function __construct(
        //Inject Model vào constructor
        protected Question $questionModel,
        protected QuestionOption $question_optionModel,
        protected ExamAttempt $exam_attemptModel,
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
                'is_shared' => $data['is_shared'] ?? false,
                'type' => $data['type'],
                'answer_text' => $data['answer_text'] ?? null,
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

    //Cập nhật câu hỏi
    public function updateQuestion(Question $question, array $data): Question
    {
        return DB::transaction(function () use ($question, $data) {

            $question->update([
                'category_id' => $data['category_id'],
                'content' => $data['content'],
                'explanation' => $data['explanation'] ?? null,
                'difficulty' => $data['difficulty'],
                'type' => $data['type'],
                'answer_text' => $data['answer_text'] ?? null,
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

    //Import câu hỏi từ file
    public function importQuestions(
        UploadedFile $file,
        int $authorId
    ): int
    {
        $import = new QuestionsImport(
            $this,
            $authorId
        );

        Excel::import(
            $import,
            $file
        );

        return $import->getImportedCount();
    }

    

    /*
    |--------------------------------------------------------------------------
    | Exam Attempt Methods
    |--------------------------------------------------------------------------
    */
    // Hàm check quyền truy cập bài thi
    public function validateAttemptAccess(ExamAttempt $attempt, ?User $user=null): void
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
        // if ($attempt->isExpired()) {
        //     $attempt->update(['status' => 'expired']);
        //     throw new \Exception('Bài thi đã hết hạn.');
        // }
    }

    // Hàm kiểm tra hết hạn bài thi
    public function checkAttemptExpiration(ExamAttempt $attempt): bool
    {
        return $attempt->isExpired();
    }

    // Hàm lấy danh sách câu hỏi cho mỗi attempt
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
        
        // Shuffle if official exam mode - trộn khi đề thi có chế độ là official - chưa test
        if ($exam->mode == 'official') {
            $questions = $questions->shuffle();
        }
        
        // Persist order for consistency - cache thứ tư hiển thi câu hỏi cho mỗi attempt
        $this->persistQuestionOrder(
            $attempt, 
            $questions->pluck('id')->toArray()
        );
        
        return $questions;
    }

    // Lấy câu hỏi đã xáo trộn
    public function getShuffledQuestions(Exam $exam): Collection
    {
        return $exam->questions()
            ->with('options')
            ->get()
            ->shuffle();
    }
    // hàm lưu trữ thứ tự câu hỏi mỗi attempt
    public function persistQuestionOrder(ExamAttempt $attempt, array $questionIds): void
    {
        // Store in cache for 24 hours (longer than any exam duration)
        Cache::put(
            "attempt_{$attempt->id}_question_order",//key cache để lưu trữ thứ tự câu hỏi mỗi attempt
            $questionIds,
            now()->addHours(24)
        );
    }
    // Hàm lấy thứ tự câu hỏi đã được lưu trong cache
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
        DB::transaction(function () use ($attempt) {
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
        });
        
        // Step 6: Dispatch background grading job (outside transaction)
        GradeExamAttemptJob::dispatch($attempt->id);
    }

    //Hàm query chuẩn cho danh sách bài thi
    private function baseListQuery()
    {
      return Exam::query()
          ->select([
              'id',
              'slug',
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
          ->latest();
    }

    //Hàm lấy tất cả danh mục (id và tên) đang hoạt động
    public function getAllCategories(): Collection
    {
        return Category::query()
            ->select(['id', 'name'])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }
    /*
    Kết quả trả về có dạng:
    [
      App\Models\Category {
        id: 1,
        name: "PHP",
      },
      App\Models\Category {
        id: 2,
        name: "Laravel",
      },
      App\Models\Category {
        id: 3,
        name: "Java",
      },
      ...
    ]
    */

    //Hàm lấy danh sách bài kiểm tra mới nhất
    public function getExamListLatest(?int $limit = null)
    {
        $query = $this->baseListQuery();

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

    //Hàm tìm kiếm bài thi theo các tham số lọc
    //Tham số: keyword, category, type, sort
    public function search(array $filters = [])
    {

      $query = $this->baseListQuery();

      // Lọc theo từ khóa (tìm trong tiêu đề, mô tả ngắn, mô tả chi tiết)
      if (!empty($filters['keyword'])) {
          $keyword = trim($filters['keyword']);

          $query->where(function ($query) use ($keyword) {
              $query->where('title', 'like', "%{$keyword}%")
                  ->orWhere('short_description', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
          });
      }

      // Lọc theo danh mục (category_id)
      if (!empty($filters['category'])) {
          $query->where('category_id', $filters['category']);
      }

      // Lọc theo loại bài thi (multiple_choice, essay, hybrid)
      if (!empty($filters['type'])) {
          $query->where('type', $filters['type']);
      }

      // Sắp xếp theo thời gian (mặc định: mới nhất)
      if (!empty($filters['sort']) && $filters['sort'] === 'oldest') {
          $query->reorder('created_at', 'asc');
      }

      // 10 bài thi / trang
      return $query->paginate(10);
    }
    

    //Hàm chi tiết lấy 1 bài kiểm tra bằng slug 
    public function getExamBySlug(string $examSlug)
    {
    return Exam::query()
        ->select([
            'id',
            'slug',
            'title',
            'description',
            'duration_minutes',
            'type',
            'pass_percent',
            'author_id',
            'category_id',
            'publish_at',
        ])
        ->with([
            'author:id,name',
            'category:id,name',
        ])
        ->withCount('questions')
        ->where('slug', $examSlug)
        ->firstOrFail();
    }
    /*
    Ket qua tra ve co dang:
    id: 1,
    slug: "de-thi-laravel-co-ban",
    title: "Đề thi Laravel cơ bản",
    description: "Đề thi dùng để test giao diện làm bài.",
    duration_minutes: 30,
    type: "hybrid",
    pass_percent: "50.00",
    author_id: 1,
    category_id: 1,
    questions_count: 3,
    author: Modules\Auth\Models\User {#9019
      id: 1,
      name: "admin",
    },
    category: App\Models\Category {#9022
      id: 1,
      name: "PHP",
    }
    */

    // Hàm lấy toàn bộ tag
    public function getAllTags(): array
    {
        return Tag::orderBy('name', 'asc')->pluck('name', 'id')->toArray();
    }
    /*
    Ket qua tra ve co dang:
    [
      1 => "PHP",
      2 => "Laravel",
      3 => "Java",
      ...    
    ]
    */
   
    

    //Hàm bắt đầu làm bài kiểm tra
    public function startExam(
        Exam $exam,
        ?User $user = null,
        // ?string $sessionId = null,
        array $sessionIds = null,
    ): ExamAttempt {
        //validate đề thi hợp lệ để vào thi
        if (! $exam->publish_at) {
            throw ValidationException::withMessages([
                'exam' => 'Bài kiểm tra chưa được công bố.',
            ]);
        }

        if ($exam->publish_at->isFuture()) {
            throw ValidationException::withMessages([
                'exam' => 'Bài kiểm tra chưa đến thời gian mở.',
            ]);
        }

        if ($exam->duration_minutes <= 0) {
            throw ValidationException::withMessages([
                'exam' => 'Bài kiểm tra chưa cấu hình thời gian làm bài.',
            ]);
        }

        if (! $exam->questions()->exists()) {
            throw ValidationException::withMessages([
                'exam' => 'Bài kiểm tra chưa có câu hỏi.',
            ]);
        }
        //check phiên làm bài đã có
        $attempt = null;
        if ($user) {

            $attempt = $this->exam_attemptModel::query()
                ->where('exam_id', $exam->id)
                ->where('user_id', $user->id)
                ->where('status', 'in_progress')
                ->first();

        } elseif (!empty($sessionIds)) {
            // Tìm trong nhiều session
            $attempt = $this->exam_attemptModel::query()
                ->where('exam_id', $exam->id)
                ->whereIn('session_id', $sessionIds)
                ->where('status', 'in_progress')
                ->latest('started_at')
                ->first();
        }

        if ($attempt) {
            return $attempt;
        }

        //tạo phiên làm bài nếu chưa có
        return $this->exam_attemptModel::create([
            'exam_id'         => $exam->id,
            'user_id'         => $user?->id,
            'session_id'      => (string) Str::uuid(),

            'started_at'      => now(),

            'expires_at'      => now()->addMinutes(
                $exam->duration_minutes
            ),

            'total_questions' => $exam
                ->questions()
                ->count(),

            'status'          => 'in_progress',
        ]);
    }

    // Hàm chấm điểm bài thi
    public function finalizeAttemptGrading(int $attemptId, ?string $teacherComment = null): array
    {
        return DB::transaction(function () use ($attemptId, $teacherComment) {
            // Load attempt with relationships
            $attempt = ExamAttempt::with(['answers', 'exam.questions'])->findOrFail($attemptId);
            
            // Get all answers for this attempt
            $answers = $attempt->answers;
            
            // Calculate statistics
            $totalQuestions = $answers->count();
            $correctAnswers = $answers->where(function ($answer) {
                return $answer->status === 'correct' || $answer->is_correct === true;
            })->count();
            
            $wrongAnswers = $answers->where(function ($answer) {
                return $answer->status === 'incorrect' || $answer->is_correct === false;
            })->count();
            
            $skippedAnswers = $answers->whereNull('answered_at')->count();
            
            // Calculate total score (sum of all answer scores)
            $totalScore = $answers->sum('score');
            
            // Calculate max possible score from exam questions
            $maxScore = $attempt->exam->questions->sum('pivot.score');
            
            // Calculate percent score
            $percentScore = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
            
            // Determine if passed
            $isPassed = $percentScore >= $attempt->exam->pass_percent;
            
            // Update attempt with calculated values
            $attempt->update([
                'status' => 'completed',
                // 'submitted_at' => now(),//ko cap nhat lai submitted at
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'skipped_answers' => $skippedAnswers,
                'score' => $totalScore,
                'percent_score' => round($percentScore, 2),
                'is_passed' => $isPassed,
                'teacher_comment' => $teacherComment ?? null,
            ]);
            
            // Return statistics for notification
            return [
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'skipped_answers' => $skippedAnswers,
                'score' => $totalScore,
                'max_score' => $maxScore,
                'percent_score' => round($percentScore, 2),
                'is_passed' => $isPassed,
            ];
        });
    }

    public function approveExam(Exam $exam, User $reviewer): void
    {
        $exam->update([
            'status' => 'approved',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'rejected_reason' => null,
        ]);

        SendExamReviewEmailJob::dispatch($exam->id);
    }

    public function rejectExam(Exam $exam, User $reviewer, string $reason): void
    {
        $exam->update([
            'status' => 'rejected',
            'reviewed_by' => $reviewer->id,
            'reviewed_at' => now(),
            'rejected_reason' => $reason,
        ]);

        SendExamReviewEmailJob::dispatch($exam->id);
    }

    //Hàm lưu (tạo hoặc cập nhật) Exam
    public function saveExam(array $validated, ?int $examId=null,int $authorId){
        $exam = $examId
            ? Exam::findOrFail($examId)
            : new Exam();

        $exam->fill($validated);

        if (! $exam->exists) {
            $exam->slug=Exam::generateUniqueSlug($validated['title']);
            $exam->author_id = $authorId;
            $exam->public_id = (string) Str::uuid();
        }

        $exam->save();

        return $exam;
    }
}
