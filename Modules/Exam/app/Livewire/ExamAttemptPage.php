<?php

namespace Modules\Exam\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use WireUi\Traits\WireUiActions;

use Modules\Exam\Models\ExamAttempt;
use Modules\Exam\Models\Question;
use Modules\Exam\Services\ExamService;
use Illuminate\Support\Collection;

#[Layout('layouts.user')]
#[Title('Làm bài thi')]
class ExamAttemptPage extends Component
{
    use WireUiActions;

    // Core properties
    public ExamAttempt $attempt;
    public array $questionIds = []; // Only store IDs (lightweight)
    public int $currentQuestionIndex = 0;
    
    // UI state
    public bool $showQuestionModal = false;
    public bool $showSubmitModal = false;
    public int $tabSwitchCount = 0;
    public bool $showWarningModal = false;
    public bool $showFullscreenModal = false;
    
    // User answers - keyed by question_id
    public array $userAnswers = [];
    
    // Internal cache (not serialized in requests)
    private ?Collection $_questionsCache = null;

    // Validation rules
    protected function rules()
    {
        return [
            'userAnswers.*' => 'nullable|string|max:100', // Essay max 100 chars
        ];
    }

    public function mount(ExamAttempt $attempt, ExamService $examService)
    {
        try {
            // Load attempt with relationships
            $this->attempt = $attempt->load('exam', 'answers');
            
            // Validate access
            $examService->validateAttemptAccess($this->attempt, auth()->user());
            
            // Load questions - store IDs only, cache collection internally
            $questions = $examService->loadQuestionsForAttempt($this->attempt);
            $this->questionIds = $questions->pluck('id')->toArray();
            $this->_questionsCache = $questions; // Cache for this request
            
            // Load existing answers
            $existingAnswers = $examService->loadExistingAnswers($this->attempt);
            
            // Map answers to userAnswers array
            foreach ($existingAnswers as $answer) {
                if ($answer->selected_option_ids) {
                    // Choice questions - store as array
                    $this->userAnswers[$answer->question_id] = $answer->selected_option_ids;
                } else {
                    // Essay questions - store as string
                    $this->userAnswers[$answer->question_id] = $answer->answer_text ?? '';
                }
            }
            
            // Initialize all unanswered questions to ensure Livewire tracking
            // This fixes the bug where first edit doesn't trigger updatedUserAnswers hook
            foreach ($this->questions() as $question) {
                if (!isset($this->userAnswers[$question->id])) {
                    // Initialize based on question type
                    if ($question->type === 'essay') {
                        $this->userAnswers[$question->id] = '';
                    } else {
                        // single_choice or multiple_choice
                        $this->userAnswers[$question->id] = [];
                    }
                }
            }
            
            // Load existing violation count
            $this->tabSwitchCount = $this->attempt->violation_count ?? 0;
            if($this->attempt->exam->mode=='official'){
                $this->showFullscreenModal=true;
            }

            // Hiển thị thông báo nếu vừa đăng nhập và link attempts thành công
            if (session()->has('success')) {
                $this->notification()->success(
                    title: 'Thành công',
                    description: session('success')
                );
            }
            
        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Lỗi',
                description: $e->getMessage()
            );
            
            // Redirect back after 2 seconds
            $this->redirect(route('exam.index'), navigate: true);
        }
    }

    /**
     * Livewire lifecycle hook - called when userAnswers property updates
     * Auto-saves essay answers when user types and blurs
     */
    public function updatedUserAnswers($value, $key)
    {
        // $key = question_id
        // $value = answer value (string for essay)
        
        if ($this->attempt->status !== 'in_progress') {
            return;
        }
        
        try {
            $examService = app(ExamService::class);
            $examService->saveAttemptAnswer($this->attempt, (int)$key, $value);
            
            // Dispatch event for UI feedback
            $this->dispatch('answer-saved');
        } catch (\Exception $e) {
            // Silent fail - don't interrupt user's workflow
            \Log::error('Essay auto-save failed', [
                'attempt_id' => $this->attempt->id,
                'question_id' => $key,
                'error' => $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Computed Properties
    |--------------------------------------------------------------------------
    */

    /**
     * Get questions collection (cached for performance)
     * Returns cached collection or loads from question IDs
     */
    public function questions(): Collection
    {
        if ($this->_questionsCache === null) {
            $this->_questionsCache = $this->loadQuestionsFromIds();
        }
        
        return $this->_questionsCache;
    }

    /**
     * Load questions from stored IDs
     * Maintains the shuffled order from initial load
     */
    private function loadQuestionsFromIds(): Collection
    {
        if (empty($this->questionIds)) {
            return collect([]);
        }

        // Load questions in the order of stored IDs
        return Question::with('options')
            ->whereIn('id', $this->questionIds)
            ->get()
            ->sortBy(function ($question) {
                return array_search($question->id, $this->questionIds);
            })
            ->values();
    }

    public function getCurrentQuestion()
    {
        return $this->questions()[$this->currentQuestionIndex] ?? null;
    }

    public function getAnsweredCount(): int
    {
        return count(array_filter($this->userAnswers, function ($answer) {
            if (is_array($answer)) {
                return !empty($answer);
            }
            return !empty(trim($answer));
        }));
    }

    public function getProgress(): float
    {
        if ($this->questions()->isEmpty()) {
            return 0;
        }
        
        return round(($this->getAnsweredCount() / $this->questions()->count()) * 100, 1);
    }

    public function isQuestionAnswered(int $index): bool
    {
        $question = $this->questions()[$index] ?? null;
        
        if (!$question) {
            return false;
        }
        
        $answer = $this->userAnswers[$question->id] ?? null;
        
        if (is_array($answer)) {
            return !empty($answer);
        }
        
        return !empty(trim($answer));
    }

    public function getUnansweredQuestionNumbers(): array
    {
        $unanswered = [];
        
        foreach ($this->questions() as $index => $question) {
            if (!$this->isQuestionAnswered($index)) {
                $unanswered[] = $index + 1; // 1-indexed for display
            }
        }
        
        return $unanswered;
    }

    /*
    |--------------------------------------------------------------------------
    | Navigation Actions
    |--------------------------------------------------------------------------
    */

    public function goToQuestion(int $index)
    {
        if ($index >= 0 && $index < count($this->questionIds)) {
            $this->currentQuestionIndex = $index;
            $this->showQuestionModal = false;
            
            // Scroll to top
            $this->dispatch('scroll-to-top');
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questionIds) - 1) { 
            $this->currentQuestionIndex++;
            $this->dispatch('scroll-to-top');
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
            $this->dispatch('scroll-to-top');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Answer Management
    |--------------------------------------------------------------------------
    */

    public function saveAnswer(int $questionId, $answer, ?int $optionId = null)
    {
        // Check if attempt is still valid
        if ($this->attempt->status !== 'in_progress') {
            $this->notification()->error(
                title: 'Lỗi',
                description: 'Bài thi đã kết thúc. Không thể lưu câu trả lời.'
            );
            return;
        }

        try {
            $examService = app(ExamService::class);
            
            // Handle multiple choice checkbox toggle
            if ($optionId !== null) {
                $current = $this->userAnswers[$questionId] ?? [];
                
                if (!is_array($current)) {
                    $current = [];
                }
                
                if ($answer) {
                    // Checkbox checked - add option
                    if (!in_array($optionId, $current)) {
                        $current[] = $optionId;
                    }
                } else {
                    // Checkbox unchecked - remove option
                    $current = array_values(array_diff($current, [$optionId]));
                }
                
                $answer = $current;
            }
            
            // Validate essay length
            if (is_string($answer) && strlen($answer) > 100) {
                $this->notification()->error(
                    title: 'Vượt quá giới hạn',
                    description: 'Câu trả lời không được vượt quá 100 ký tự.'
                );
                return;
            }
            
            // Save to database
            $examService->saveAttemptAnswer($this->attempt, $questionId, $answer);
            
            // Update local state
            $this->userAnswers[$questionId] = $answer;
            
            // Dispatch event for UI feedback
            $this->dispatch('answer-saved');
            
        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Lỗi',
                description: 'Không thể lưu câu trả lời. Vui lòng thử lại.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Submit Action
    |--------------------------------------------------------------------------
    */

    public function submitExam()
    {
        try {
            $examService = app(ExamService::class);
            
            // Validate attempt can be submitted
            if ($this->attempt->status !== 'in_progress') {
                throw new \Exception('Bài thi đã được nộp hoặc đã hết hạn.');
            }
            
            // Submit the attempt
            $examService->submitAttempt($this->attempt);
            
            // Refresh model
            $this->attempt->refresh();
            
            // Close modal
            $this->showSubmitModal = false;
            
            // Success notification
            $this->notification()->success(
                title: 'Nộp bài thành công',
                description: 'Bài thi của bạn đã được lưu lại.'
            );
            
            // Dispatch event to disable form
            $this->dispatch('exam-submitted');
            
        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Lỗi',
                description: $e->getMessage()
            );
        }
    }

    #[On('tab-switched')]
    public function recordTabSwitch(): void
    {
        // Only count violations if exam is still in progress
        if ($this->attempt->status !== 'in_progress' || $this->attempt->exam->mode!= 'official') {
            return;
        }

        $this->tabSwitchCount++;
        
        // Save to database immediately
        $this->attempt->update([
            'violation_count' => $this->tabSwitchCount
        ]);
        
        $this->showWarningModal = true;
    }

    #[On('fullscreen-exited')]
    public function handleFullscreenExit(): void
    {
        // Only require fullscreen for official exams
        if ($this->attempt->exam->mode !== 'official') {
            return;
        }
        
        // Only show modal if exam is still in progress
        if ($this->attempt->status !== 'in_progress') {
            return;
        }
        
        $this->showFullscreenModal = true;
    }
    
    public function requestFullscreen(): void
    {
        $this->showFullscreenModal = false;
        $this->dispatch('enter-fullscreen');
    }

    public function autoSubmitExam()
    {
        $this->attempt->refresh();

        if ($this->attempt->status !== 'in_progress') {
            return;
        }

        $this->submitExam();
    }

    public function render()
    {
        return view('exam::livewire.exam-attempt-page');
    }
}
