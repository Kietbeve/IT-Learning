<?php

namespace Modules\Exam\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use WireUi\Traits\WireUiActions;

use Modules\Exam\Services\ExamService;

use Modules\Exam\Models\Exam;
#[Layout("layouts.admin")]
class ExamReviewPage extends Component
{
    use WireUiActions;
    protected ExamService $examService;
    public function boot(ExamService $examService)
    {
        $this->examService = $examService;
    }
    public Exam $exam;

    public string $rejectedReason = '';

    public function mount(Exam $exam)
    {
        $this->exam = $exam->load([
            'author',
            'category',
            'questions.options',
        ]);

        $this->rejectedReason = $exam->rejected_reason ?? '';
    }

    #[Computed]
    public function totalQuestions(): int
    {
        return $this->exam->questions->count();
    }

    #[Computed]
    public function totalScore(): float
    {
        return $this->exam->questions->sum(
            fn ($question) => $question->pivot->score
        );
    }

    #[Computed]
    public function qualityCheck(): array
    {
        $missingCorrectAnswer = 0;
        $missingExplanation = 0;

        foreach ($this->exam->questions as $question) {

            $correctCount = $question->options
                ->where('is_correct', true)
                ->count();

            if ($correctCount === 0) {
                $missingCorrectAnswer++;
            }

            if (blank($question->explanation)) {
                $missingExplanation++;
            }
        }

        return [
            'missing_correct_answer' => $missingCorrectAnswer,
            'missing_explanation' => $missingExplanation,
        ];
    }

    public function approve()
    {
        $this->examService->approveExam($this->exam, auth()->user());

        $this->notification()->success(//tạm thời chỉ thông báo
            title: 'Thành công',
            description: 'Đề thi đã được duyệt.'
        );

        $this->redirectRoute('admin.moderation.exam');
    }

    public function reject()
    {
        $this->validate([
            'rejectedReason' => [
                'required',
                'min:10',
            ],
        ]);

        $this->examService->rejectExam($this->exam, auth()->user(), $this->rejectedReason);

        $this->notification()->success(
            title: 'Thành công',
            description: 'Đề thi đã bị từ chối.'
        );

        $this->redirectRoute('admin.moderation.exam');
    }

    public function render()
    {
        return view(
            'exam::admin.livewire.exam-review-page'
        );
    }
}