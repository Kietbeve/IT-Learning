<?php

namespace Modules\Exam\Livewire\Contributor;

use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Exam\Models\AttemptAnswer;
use Modules\Exam\Models\QuestionOption;
use WireUi\Traits\WireUiActions;

class AttemptAnswerModal extends Component
{
    use WireUiActions;

    public bool $showModal = false;
    public bool $showCommentModal = false;
    public string $status = '';
    public string $teacherComment = '';
    public int $attemptAnswerId;
    public ?AttemptAnswer $answer = null;

    /**
     * Listen to open-answer event and load the answer details
     */
    #[On('open-answer')]
    public function openModal(int $answerId): void
    {
        $this->resetModal();

        // Load the answer with all necessary relationships
        $this->answer = AttemptAnswer::with([
            'question.options',
            'question.category',
            'attempt.exam.questions', // Need this to get sort_order from pivot
        ])->findOrFail($answerId);

        $this->showModal = true;
    }

    /**
     * Close modal and reset data
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['answer']);
    }

    /**
     * Reset modal state
     */
    public function resetModal(): void
    {
        $this->reset(['showModal', 'answer']);
    }

    /**
     * Check if an option was selected by the user
     */
    public function isOptionSelected(int $optionId): bool
    {
        if (!$this->answer || !$this->answer->selected_option_ids) {
            return false;
        }

        return in_array($optionId, $this->answer->selected_option_ids);
    }

    /**
     * Check if an option is the correct answer
     */
    public function isCorrectOption(QuestionOption $option): bool
    {
        return $option->is_correct;
    }

    /**
     * Get the status badge information
     */
    public function getStatusBadge(): array
    {
        if (!$this->answer) {
            return [
                'label' => 'N/A',
                'color' => 'gray',
                'icon' => 'question-mark-circle',
            ];
        }

        // Skipped question
        if ($this->answer->answered_at === null) {
            return [
                'label' => 'Bỏ qua',
                'color' => 'gray',
                'icon' => 'minus-circle',
            ];
        }

        // Answered question
        if ($this->answer->status=='correct') {
            return [
                'label' => 'Đúng',
                'color' => 'positive',
                'icon' => 'check-circle',
            ];
        }
        else if($this->answer->status=='incorrect'){
            return [
                'label' => 'Sai',
                'color' => 'negative',
                'icon' => 'x-circle',
            ];
        }

        return [
            'label' => 'Đang chấm',
            'color' => 'warning',
            'icon' => 'clock',
        ];
    }

    /**
     * Get the border color class for the modal card
     */
    public function getBorderColorClass(): string
    {
        if (!$this->answer) {
            return 'border-gray-400';
        }

        if ($this->answer->answered_at === null) {
            return 'border-gray-400';
        }

        return $this->answer->is_correct ? 'border-green-500' : 'border-red-500';
    }

    /**
     * Get the question sort order from the exam
     */
    public function getQuestionSortOrder(): int
    {
        if (!$this->answer) {
            return 0;
        }

        $examQuestion = $this->answer->attempt->exam->questions
            ->firstWhere('id', $this->answer->question_id);

        return $examQuestion?->pivot->sort_order ?? 0;
    }

    /**
     * Get the score for this question
     */
    public function getQuestionScore(): float
    {
        if (!$this->answer) {
            return 0;
        }

        return $this->answer->score ?? 0;
    }

    /**
     * Get the maximum score for this question
     */
    public function getQuestionMaxScore(): float
    {
        if (!$this->answer) {
            return 0;
        }

        $examQuestion = $this->answer->attempt->exam->questions
            ->firstWhere('id', $this->answer->question_id);

        return $examQuestion?->pivot->score ?? 0;
    }

    /**
     * Get option styling classes based on correctness and selection
     */
    public function getOptionClasses(QuestionOption $option): array
    {
        $isCorrect = $this->isCorrectOption($option);
        $isSelected = $this->isOptionSelected($option->id);

        // Determine styling based on correctness and selection
        if ($isCorrect && $isSelected) {
            // User selected the correct answer
            return [
                'container' => 'bg-green-50 border-2 border-green-500',
                'icon_container' => 'bg-green-500',
                'icon_type' => 'check',
                'text' => 'text-green-900 font-semibold',
                'show_user_badge' => true,
                'show_correct_badge' => true,
                'badge_color' => 'positive',
            ];
        } elseif ($isCorrect && !$isSelected) {
            // Correct answer but user didn't select it
            return [
                'container' => 'bg-green-50 border-2 border-green-500',
                'icon_container' => 'bg-green-500',
                'icon_type' => 'check',
                'text' => 'text-green-900 font-semibold',
                'show_user_badge' => false,
                'show_correct_badge' => true,
                'badge_color' => 'positive',
            ];
        } elseif (!$isCorrect && $isSelected) {
            // User selected wrong answer
            return [
                'container' => 'bg-red-50 border-2 border-red-500',
                'icon_container' => 'bg-red-500',
                'icon_type' => 'x-mark',
                'text' => 'text-red-900 font-semibold',
                'show_user_badge' => true,
                'show_correct_badge' => false,
                'badge_color' => 'negative',
            ];
        } else {
            // Neither correct nor selected
            $containerClass = 'bg-gray-50 border border-gray-200';
            
            // Add opacity for skipped questions
            if ($this->answer->answered_at === null) {
                $containerClass .= ' opacity-60';
            }

            return [
                'container' => $containerClass,
                'icon_container' => 'border-2 border-gray-300',
                'icon_type' => null,
                'text' => 'text-gray-700',
                'show_user_badge' => false,
                'show_correct_badge' => false,
                'badge_color' => null,
            ];
        }
    }

    #[On('attempt-answer-grade')]
    public function open(int $attemptAnswerId, string $status)
    {
        $this->attemptAnswerId = $attemptAnswerId;
        $answer = AttemptAnswer::findOrFail($this->attemptAnswerId);
        $this->status = $status;// status truyền từ button chấm

        $this->teacherComment = $answer->teacher_comment??'';

        $this->showCommentModal = true;
    }

    public function save()
    {
        $answer = AttemptAnswer::findOrFail($this->attemptAnswerId);

        $answer->update([
            'status' => $this->status,
            'teacher_comment' => $this->teacherComment,
            'is_correct' => $this->status === 'correct',
        ]);

        $this->dispatch('attempt-answer-updated');
        $this->showCommentModal = false;
        $this->notification()->success(
            title: 'Đã cập nhật!',
            description: "Câu {$answer->question?->pivot?->sort_order} được đánh dấu là {$this->status}"
        );
        $this->dispatch('pg:eventRefresh-attempt-answer-table');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('exam::contributor.livewire.attempt-answer-modal');
    }
}
