<?php

namespace Modules\Learning\Livewire\Manage;

use Livewire\Component;
use Modules\Learning\Models\LessonQuiz;
use Modules\Learning\Models\LessonQuizQuestion;
use Modules\Learning\Services\LessonQuizService;
use Illuminate\Support\Facades\DB;

class QuizQuestionManagement extends Component
{
    public $lessonId;
    public $quizId;
    public $quiz;
    public $questions;
    
    // Form properties
    public $questionId;
    public $type = 'single_choice';
    public $question_text;
    public $explanation;
    public $score = 1;
    public $options = [];
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $deleteQuestionId;
    
    // Temporary option input
    public $newOptionKey = 'A';
    public $newOptionText = '';
    public $newOptionIsCorrect = false;
    
    protected $quizService;

    public function boot(LessonQuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function mount($lessonId, $quizId)
    {
        $this->lessonId = $lessonId;
        $this->quizId = $quizId;
        $this->quiz = LessonQuiz::with('lesson')->findOrFail($quizId);
        $this->loadQuestions();
        
        // Initialize options with default structure
        $this->initializeDefaultOptions();
    }

    public function __invoke()
    {
        return $this->render();
    }

    public function loadQuestions()
    {
        $this->questions = LessonQuizQuestion::where('quiz_id', $this->quizId)
            ->orderBy('sort_order')
            ->get();
    }

    public function initializeDefaultOptions()
    {
        if (empty($this->options)) {
            $this->options = [
                ['key' => 'A', 'text' => '', 'is_correct' => false],
                ['key' => 'B', 'text' => '', 'is_correct' => false],
            ];
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->resetForm();
    }

    public function openEditModal($questionId)
    {
        $question = LessonQuizQuestion::findOrFail($questionId);
        
        $this->questionId = $question->id;
        $this->type = $question->type;
        $this->question_text = $question->question_text;
        $this->explanation = $question->explanation;
        $this->score = $question->score;
        $this->options = $question->options ?? [];
        
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($questionId)
    {
        $this->deleteQuestionId = $questionId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteQuestionId = null;
    }

    public function addOption()
    {
        if (empty($this->newOptionText)) {
            session()->flash('error', 'Nội dung đáp án không được để trống');
            return;
        }
        
        // Generate next option key
        $lastKey = empty($this->options) ? '@' : end($this->options)['key'];
        $nextKey = chr(ord($lastKey) + 1);
        
        $this->options[] = [
            'key' => $nextKey,
            'text' => $this->newOptionText,
            'is_correct' => $this->newOptionIsCorrect
        ];
        
        // Reset temporary inputs
        $this->newOptionText = '';
        $this->newOptionIsCorrect = false;
    }

    public function removeOption($index)
    {
        if (count($this->options) <= 2) {
            session()->flash('error', 'Câu hỏi phải có ít nhất 2 đáp án');
            return;
        }
        
        unset($this->options[$index]);
        $this->options = array_values($this->options); // Re-index array
    }

    public function toggleOptionCorrect($index)
    {
        // For single choice, uncheck other options
        if ($this->type === 'single_choice') {
            foreach ($this->options as $key => $option) {
                $this->options[$key]['is_correct'] = false;
            }
        }
        
        $this->options[$index]['is_correct'] = !$this->options[$index]['is_correct'];
    }

    public function createQuestion()
    {
        $this->validate([
            'type' => 'required|in:single_choice,multiple_choice,true_false',
            'question_text' => 'required|string',
            'explanation' => 'nullable|string',
            'score' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
        ]);

        // Validate at least one correct answer
        $hasCorrectAnswer = collect($this->options)->contains('is_correct', true);
        if (!$hasCorrectAnswer) {
            session()->flash('error', 'Phải có ít nhất một đáp án đúng');
            return;
        }

        // For single choice, ensure only one correct answer
        if ($this->type === 'single_choice') {
            $correctCount = collect($this->options)->where('is_correct', true)->count();
            if ($correctCount !== 1) {
                session()->flash('error', 'Câu hỏi một lựa chọn chỉ được có một đáp án đúng');
                return;
            }
        }

        try {
            $this->quizService->addQuestion($this->quizId, [
                'type' => $this->type,
                'question_text' => $this->question_text,
                'options' => $this->options,
                'explanation' => $this->explanation,
                'score' => $this->score,
            ]);

            $this->loadQuestions();
            $this->closeCreateModal();
            
            session()->flash('success', 'Câu hỏi đã được thêm thành công!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function updateQuestion()
    {
        $this->validate([
            'type' => 'required|in:single_choice,multiple_choice,true_false',
            'question_text' => 'required|string',
            'explanation' => 'nullable|string',
            'score' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
        ]);

        // Validate at least one correct answer
        $hasCorrectAnswer = collect($this->options)->contains('is_correct', true);
        if (!$hasCorrectAnswer) {
            session()->flash('error', 'Phải có ít nhất một đáp án đúng');
            return;
        }

        // For single choice, ensure only one correct answer
        if ($this->type === 'single_choice') {
            $correctCount = collect($this->options)->where('is_correct', true)->count();
            if ($correctCount !== 1) {
                session()->flash('error', 'Câu hỏi một lựa chọn chỉ được có một đáp án đúng');
                return;
            }
        }

        try {
            $this->quizService->updateQuestion($this->questionId, [
                'type' => $this->type,
                'question_text' => $this->question_text,
                'options' => $this->options,
                'explanation' => $this->explanation,
                'score' => $this->score,
            ]);

            $this->loadQuestions();
            $this->closeEditModal();
            
            session()->flash('success', 'Câu hỏi đã được cập nhật thành công!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->questionId = null;
        $this->type = 'single_choice';
        $this->question_text = '';
        $this->explanation = '';
        $this->score = 1;
        $this->initializeDefaultOptions();
        $this->newOptionText = '';
        $this->newOptionIsCorrect = false;
        
        $this->resetErrorBag();
    }

    public function deleteQuestion()
    {
        try {
            $this->quizService->deleteQuestion($this->deleteQuestionId);
            
            $this->loadQuestions();
            $this->closeDeleteModal();
            
            session()->flash('success', 'Câu hỏi đã được xóa thành công!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function updateQuestionsOrder($orderedIds)
    {
        try {
            $this->quizService->reorderQuestions($this->quizId, $orderedIds);
            $this->loadQuestions();
            
            session()->flash('success', 'Đã cập nhật thứ tự câu hỏi!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function moveQuestionUp($questionId)
    {
        $questions = $this->questions->sortBy('sort_order')->values();
        $currentIndex = $questions->search(function ($q) use ($questionId) {
            return $q->id === $questionId;
        });

        if ($currentIndex > 0) {
            $newOrder = $questions->pluck('id')->toArray();
            $temp = $newOrder[$currentIndex];
            $newOrder[$currentIndex] = $newOrder[$currentIndex - 1];
            $newOrder[$currentIndex - 1] = $temp;
            
            $this->updateQuestionsOrder($newOrder);
        }
    }

    public function moveQuestionDown($questionId)
    {
        $questions = $this->questions->sortBy('sort_order')->values();
        $currentIndex = $questions->search(function ($q) use ($questionId) {
            return $q->id === $questionId;
        });

        if ($currentIndex < $questions->count() - 1) {
            $newOrder = $questions->pluck('id')->toArray();
            $temp = $newOrder[$currentIndex];
            $newOrder[$currentIndex] = $newOrder[$currentIndex + 1];
            $newOrder[$currentIndex + 1] = $temp;
            
            $this->updateQuestionsOrder($newOrder);
        }
    }

    public function getTotalScore()
    {
        return $this->questions->sum('score');
    }

    public function getQuestionTypeLabel($type)
    {
        return match($type) {
            'single_choice' => 'Một lựa chọn',
            'multiple_choice' => 'Nhiều lựa chọn',
            'true_false' => 'Đúng/Sai',
            default => $type
        };
    }

    public function render()
    {
        return view('learning::livewire.manage.quiz-question-management', [
            'quiz' => $this->quiz,
            'questions' => $this->questions,
            'totalScore' => $this->getTotalScore(),
        ]);
    }
}
