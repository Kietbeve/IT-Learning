<?php

namespace Modules\Exam\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use WireUi\Traits\WireUiActions;

use Modules\Exam\Models\Exam;
#[Layout("layouts.admin")]
class ExamReviewPage extends Component
{
    use WireUiActions;

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
        $this->exam->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejected_reason' => null,
        ]);

        // $this->redirectRoute(
        //     'contributor.exams.index'
        // );
        $this->notification()->success(//tạm thời chỉ thông báo
            title: 'Thành công',
            description: 'Đề thi đã được duyệt.'
        );
    }

    public function reject()
    {
        $this->validate([
            'rejectedReason' => [
                'required',
                'min:10',
            ],
        ]);

        $this->exam->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejected_reason' => $this->rejectedReason,
        ]);

        // $this->redirectRoute(
        //     'contributor.exams.index'
        // );
        $this->notification()->success(
            title: 'Thành công',
            description: 'Đề thi đã bị từ chối.'
        );
    }

    public function render()
    {
        return view(
            'exam::admin.livewire.exam-review-page'
        );
    }
}