<?php

namespace Modules\Exam\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

use Modules\Exam\Models\ExamAttempt;
use Modules\Exam\Models\AttemptAnswer;
use Modules\Exam\Models\QuestionOption;

#[Layout('layouts.user')]
#[Title('Kết quả bài thi')]
class ExamResultPage extends Component
{
    use WireUiActions;
    use WithPagination;

    // Core properties
    public ExamAttempt $attempt;
    public string $filter = 'all'; // 'all', 'correct', 'wrong', 'skipped'
    
    // Pagination theme
    protected $paginationTheme = 'tailwind';

    /**
     * Mount component and validate attempt access
     */
    public function mount(ExamAttempt $attempt)
    {
        // Verify attempt is submitted/completed
        if (!in_array($attempt->status, ['submitted', 'auto_submitted', 'completed', 'canceled'])) {
            $this->notification()->error(
                title: 'Lỗi truy cập',
                description: 'Bài thi chưa được nộp. Vui lòng hoàn thành bài thi trước.'
            );
            
            return redirect()->route('exam.index');
        }
        
        // Load relationships eagerly to prevent N+1 queries
        $this->attempt = $attempt->load([
            'exam.questions', // Load questions with pivot data (sort_order)
            'user',
            'answers.question.options'
        ]);
    }

    /**
     * Get filtered answers with pagination
     */
    #[Computed]
    public function filteredAnswers()
    {
        $query = $this->attempt->answers()->with(['question.options']);
        
        switch ($this->filter) {
            case 'correct':
                $query->where('is_correct', true)
                      ->whereNotNull('answered_at');
                break;
            
            case 'wrong':
                $query->where('is_correct', false)
                      ->whereNotNull('answered_at');
                break;
            
            case 'skipped':
                $query->whereNull('answered_at');
                break;
            
            // 'all' - no filter applied
        }
        
        return $query->paginate(10);
    }

    /**
     * Get exam statistics
     */
    #[Computed]
    public function statistics()
    {
        return [
            'total' => $this->attempt->total_questions ?? 0,
            'correct' => $this->attempt->correct_answers ?? 0,
            'wrong' => $this->attempt->wrong_answers ?? 0,
            'skipped' => $this->attempt->skipped_answers ?? 0,
            'score' => $this->attempt->score ?? 0,
            'percent' => $this->attempt->percent_score ?? 0,
            'passed' => $this->attempt->is_passed ?? false,
            'pass_percent' => $this->attempt->exam->pass_percent ?? 50,
            'duration' => $this->calculateDuration(),
        ];
    }

    /**
     * Set filter and reset pagination
     */
    public function setFilter(string $filter)
    {
        $this->filter = $filter;
        $this->resetPage(); // Reset to page 1 when filter changes
    }

    /**
     * Calculate exam duration in human-readable format
     */
    private function calculateDuration(): string
    {
        $started = $this->attempt->started_at;
        $submitted = $this->attempt->submitted_at;
        
        if (!$started || !$submitted) {
            return '0 phút 0 giây';
        }
        
        $diff = $started->diffInSeconds($submitted);
        $minutes = floor($diff / 60);
        $seconds = $diff % 60;
        
        return "{$minutes} phút {$seconds} giây";
    }

    /**
     * Check if an option was selected by the user
     */
    public function isOptionSelected(AttemptAnswer $answer, int $optionId): bool
    {
        if (!$answer->selected_option_ids) {
            return false;
        }
        
        return in_array($optionId, $answer->selected_option_ids);
    }

    /**
     * Check if an option is the correct answer
     */
    public function isCorrectOption(QuestionOption $option): bool
    {
        return $option->is_correct;
    }

    /**
     * Get the status color for pass/fail badge
     */
    public function getStatusColor(): string
    {
        return $this->attempt->is_passed ? 'green' : 'red';
    }

    /**
     * Get the status text for pass/fail badge
     */
    public function getStatusText(): string
    {
        return $this->attempt->is_passed ? 'ĐẠT YÊU CẦU' : 'CHƯA ĐẠT';
    }

    /**
     * Calculate percentage for a statistic
     */
    public function calculatePercentage(int $count): float
    {
        $total = $this->statistics()['total'];
        
        if ($total === 0) {
            return 0;
        }
        
        return round(($count / $total) * 100, 1);
    }

    /**
     * Get the global question number (across pagination)
     */
    public function getQuestionNumber(int $loopIteration): int
    {
        $currentPage = $this->filteredAnswers->currentPage();
        $perPage = $this->filteredAnswers->perPage();
        
        return (($currentPage - 1) * $perPage) + $loopIteration;
    }

    /**
     * Get the sort order for a question from the exam's question list
     */
    public function getQuestionSortOrder(int $questionId): int
    {
        $examQuestion = $this->attempt->exam->questions->firstWhere('id', $questionId);
        return $examQuestion?->pivot->sort_order ?? 0;
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('exam::livewire.exam-result-page');
    }
}
