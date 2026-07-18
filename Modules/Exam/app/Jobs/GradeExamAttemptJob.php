<?php

namespace Modules\Exam\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Modules\Exam\Models\ExamAttempt;
use Modules\Exam\Models\AttemptAnswer;

class GradeExamAttemptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public int $timeout = 300;

    /**
     * Delete the job if its models no longer exist.
     */
    public bool $deleteWhenMissingModels = true;

    /**
     * Exam attempt ID (store ID instead of model for better serialization)
     */
    public int $attemptId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $attemptId)
    {
        $this->attemptId = $attemptId;
    }

    /**
     * Execute the job - Grade the exam attempt
     */
    public function handle(): void
    {
        Log::info("Starting exam grading", ['attempt_id' => $this->attemptId]);

        DB::transaction(function () {
            // Load attempt with all necessary relationships (eager loading to avoid N+1)
            $attempt = ExamAttempt::with([
                'exam.questions', // Need pivot data for score
                'answers.question.options',
                'exam'
            ])->findOrFail($this->attemptId);

            // Idempotency check: if already graded, skip processing
            if ($attempt->status === 'completed') {
                Log::info("Attempt already graded, skipping", ['attempt_id' => $this->attemptId]);
                return;
            }

            // Ensure attempt is in submitted status
            if (!in_array($attempt->status, ['submitted', 'auto_submitted'])) {
                Log::warning("Attempt not in submitted status", [
                    'attempt_id' => $this->attemptId,
                    'current_status' => $attempt->status,
                ]);
                return;
            }

            // Grade all answers
            $this->gradeAnswers($attempt);

            // Calculate final statistics
            $statistics = $this->calculateStatistics($attempt);

            // Update attempt with final results
            $attempt->update([
                'total_questions' => $statistics['total'],
                'correct_answers' => $statistics['correct'],
                'wrong_answers' => $statistics['wrong'],
                'skipped_answers' => $statistics['skipped'],
                'score' => $statistics['score'],
                'percent_score' => $statistics['percent'],
                'is_passed' => $statistics['is_passed'],
                // 'status' => $attempt->exam->type=='multiple_choice'?'completed':$attempt->status,
                //đổi logic hoàn thành chấm: pratice->completed, official->submitted
                'status' => $attempt->exam->mode == 'practice' ? 'completed' : 'submitted',
            ]);

            Log::info("Exam grading completed", [
                'attempt_id' => $this->attemptId,
                'score' => $statistics['score'],
                'percent' => $statistics['percent'],
                'is_passed' => $statistics['is_passed'],
            ]);
        });
    }

    /**
     * Grade all answers in the attempt
     */
    private function gradeAnswers(ExamAttempt $attempt): void
    {
        foreach ($attempt->answers as $answer) {
            $question = $answer->question;

            // Get question score from pivot table
            $questionScore = $attempt->exam->questions()
                ->where('questions.id', $question->id)
                ->first()
                ->pivot
                ->score ?? 1;

            // Skip essay questions (require manual grading)
            if ($question->type === 'essay') {
                // $answer->update([
                //     'status' => 'pending',
                //     'is_correct' => null,
                //     'score' => 0,
                // ]);
                // continue;
                $result = $this->gradeEssay($answer);

                $answer->update([
                    'status' => $result['status'],
                    'is_correct' => $result['is_correct'],
                    'score' => $result['status'] === 'correct'
                        ? $questionScore
                        : 0,
                ]);

                continue;
            }

            // Grade multiple choice questions (single_choice and multiple_choice)
            if (in_array($question->type, ['single_choice', 'multiple_choice'])) {
                $isCorrect = $this->gradeAnswer($answer);

                $answer->update([
                    'is_correct' => $isCorrect,
                    'status' => $isCorrect ? 'correct' : 'incorrect',
                    'score' => $isCorrect ? $questionScore : 0,
                ]);
            }
        }
    }

    /**
     * Grade a single answer by comparing with correct options
     * All-or-nothing grading: must select exactly all correct options
     * Hàm chấm trắc nghiệm
     */
    private function gradeAnswer(AttemptAnswer $answer): bool
    {
        // If no answer provided or not answered, it's incorrect
        if (empty($answer->selected_option_ids) || $answer->answered_at === null) {
            return false;
        }

        // Get all correct option IDs for this question
        $correctOptionIds = $answer->question->options()
            ->where('is_correct', true)
            ->pluck('id')
            ->toArray();

        // Get user's selected option IDs
        $selectedOptionIds = $answer->selected_option_ids;

        // Sort both arrays for comparison
        sort($correctOptionIds);
        sort($selectedOptionIds);

        // All-or-nothing: must select exactly all correct options and no incorrect ones
        return $correctOptionIds === $selectedOptionIds;
    }

    /**
     * Calculate final statistics for the exam attempt
     */
    private function calculateStatistics(ExamAttempt $attempt): array
    {
        // Reload answers to get updated values after grading
        $answers = $attempt->answers()->get();

        $total = $answers->count();
        $correct = $answers->where('is_correct', true)->count();
        $wrong = $answers->where('is_correct', false)
            ->whereNotNull('answered_at')
            ->count();
        $skipped = $answers->whereNull('answered_at')->count();

        // Calculate total score (sum of earned points)
        $totalScore = $answers->sum('score');

        // Calculate maximum possible score from exam questions
        $maxScore = $attempt->exam->questions()->sum('exam_questions.score');

        // Calculate percentage (avoid division by zero)
        $percentScore = $maxScore > 0
            ? round(($totalScore / $maxScore) * 100, 2)
            : 0;

        // Determine pass/fail based on exam's pass percentage
        $passPercent = $attempt->exam->pass_percent ?? 50;
        $isPassed = $percentScore >= $passPercent;

        return [
            'total' => $total,
            'correct' => $correct,
            'wrong' => $wrong,
            'skipped' => $skipped,
            'score' => $totalScore,
            'percent' => $percentScore,
            'is_passed' => $isPassed,
        ];
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("Exam grading job failed", [
            'attempt_id' => $this->attemptId,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
        ]);

        // Optionally: You can add logic here to notify admins or update attempt status
    }

    private function gradeEssay(AttemptAnswer $answer): array
    {
        $correct = $this->normalize($answer->question->answer_text);
        $student = $this->normalize($answer->answer_text);

        if ($student === '') {
            return [
                'is_correct' => false,
                'status' => 'incorrect',
                'score_percent' => 0,
            ];
        }

        similar_text($student, $correct, $percent);// ham so sanh muc do giong nhau co san cua php

        if ($percent >= 95) {
            return [
                'is_correct' => true,
                'status' => 'correct',
                'score_percent' => 100,
            ];
        }

        if ($percent >= 80) {
            return [
                'is_correct' => null,
                'status' => 'pending',
                'score_percent' => $percent,
            ];
        }

        return [
            'is_correct' => false,
            'status' => 'incorrect',
            'score_percent' => $percent,
        ];
    }

    //hàm chuan hoa chuoi tẽ
    private function normalize(string $text): string
    {
        // bỏ toàn bộ html trong chuoi
        $text = strip_tags($text);
        //chuyển về dạng không có html entity
        $text = html_entity_decode($text);
        //chuyển về dạng chữ thường
        $text = mb_strtolower($text);
        // bỏ dấu
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        // bỏ ký tự đặc biệt
        $text = preg_replace('/[^a-z0-9\s]/', '', $text);
        // gom khoảng trắng
        $text = preg_replace('/\s+/', ' ', trim($text));

        return $text;
    }
}
