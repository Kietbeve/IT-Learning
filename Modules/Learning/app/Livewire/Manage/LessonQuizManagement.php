<?php

namespace Modules\Learning\Livewire\Manage;

use Livewire\Component;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\LessonQuiz;
use Modules\Learning\Services\LessonQuizService;
use Illuminate\Support\Facades\Auth;

class LessonQuizManagement extends Component
{
    public $lessonId;
    public $lesson;
    public $quizzes;
    
    // Form properties
    public $quizId;
    public $title;
    public $description;
    public $duration_minutes;
    public $pass_score = 60;
    public $max_attempts;
    public $shuffle_questions = false;
    public $shuffle_options = false;
    public $show_correct_answers = true;
    public $allow_review = true;
    public $is_required = false;
    public $is_published = true;
    
    // Modal states
    public $showCreateModal = false;
    public $showEditModal = false;
    public $showDeleteModal = false;
    public $deleteQuizId;
    
    protected $quizService;

    public function boot(LessonQuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function mount($lessonId)
    {
        $this->lessonId = $lessonId;
        $this->lesson = RoadmapLesson::findOrFail($lessonId);
        $this->loadQuizzes();
    }

    public function __invoke()
    {
        return $this->render();
    }

    public function loadQuizzes()
    {
        $this->quizzes = LessonQuiz::where('lesson_id', $this->lessonId)
            ->withCount('questions')
            ->withCount('attempts')
            ->get();
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

    public function openEditModal($quizId)
    {
        $quiz = LessonQuiz::findOrFail($quizId);
        
        $this->quizId = $quiz->id;
        $this->title = $quiz->title;
        $this->description = $quiz->description;
        $this->duration_minutes = $quiz->duration_minutes;
        $this->pass_score = $quiz->pass_score;
        $this->max_attempts = $quiz->max_attempts;
        $this->shuffle_questions = $quiz->shuffle_questions;
        $this->shuffle_options = $quiz->shuffle_options;
        $this->show_correct_answers = $quiz->show_correct_answers;
        $this->allow_review = $quiz->allow_review;
        $this->is_required = $quiz->is_required;
        $this->is_published = $quiz->is_published;
        
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->resetForm();
    }

    public function openDeleteModal($quizId)
    {
        $this->deleteQuizId = $quizId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->deleteQuizId = null;
    }

    public function createQuiz()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
        ]);

        try {
            $this->quizService->createQuiz($this->lessonId, [
                'title' => $this->title,
                'description' => $this->description,
                'duration_minutes' => $this->duration_minutes,
                'pass_score' => $this->pass_score,
                'max_attempts' => $this->max_attempts,
                'shuffle_questions' => $this->shuffle_questions,
                'shuffle_options' => $this->shuffle_options,
                'show_correct_answers' => $this->show_correct_answers,
                'allow_review' => $this->allow_review,
                'is_required' => $this->is_required,
                'is_published' => $this->is_published,
            ]);

            $this->loadQuizzes();
            $this->closeCreateModal();
            
            session()->flash('success', 'Quiz đã được tạo thành công!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function updateQuiz()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'pass_score' => 'required|integer|min:0|max:100',
            'max_attempts' => 'nullable|integer|min:1',
        ]);

        try {
            $this->quizService->updateQuiz($this->quizId, [
                'title' => $this->title,
                'description' => $this->description,
                'duration_minutes' => $this->duration_minutes,
                'pass_score' => $this->pass_score,
                'max_attempts' => $this->max_attempts,
                'shuffle_questions' => $this->shuffle_questions,
                'shuffle_options' => $this->shuffle_options,
                'show_correct_answers' => $this->show_correct_answers,
                'allow_review' => $this->allow_review,
                'is_required' => $this->is_required,
                'is_published' => $this->is_published,
            ]);

            $this->loadQuizzes();
            $this->closeEditModal();
            
            session()->flash('success', 'Quiz đã được cập nhật thành công!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function deleteQuiz()
    {
        try {
            $this->quizService->deleteQuiz($this->deleteQuizId);
            
            $this->loadQuizzes();
            $this->closeDeleteModal();
            
            session()->flash('success', 'Quiz đã được xóa thành công!');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function togglePublished($quizId)
    {
        try {
            $quiz = LessonQuiz::findOrFail($quizId);
            $quiz->is_published = !$quiz->is_published;
            $quiz->save();
            
            $this->loadQuizzes();
            
            $status = $quiz->is_published ? 'công khai' : 'ẩn';
            session()->flash('success', "Quiz đã được chuyển sang trạng thái {$status}!");
            
        } catch (\Exception $e) {
            session()->flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function resetForm()
    {
        $this->quizId = null;
        $this->title = '';
        $this->description = '';
        $this->duration_minutes = null;
        $this->pass_score = 60;
        $this->max_attempts = null;
        $this->shuffle_questions = false;
        $this->shuffle_options = false;
        $this->show_correct_answers = true;
        $this->allow_review = true;
        $this->is_required = false;
        $this->is_published = true;
        
        $this->resetErrorBag();
    }

    public function goToQuestionManagement($quizId)
    {
        return redirect()->route('manage.lessons.quiz.questions', [
            'lessonId' => $this->lessonId,
            'quizId' => $quizId
        ]);
    }

    public function getQuizStats($quiz)
    {
        return [
            'total_questions' => $quiz->questions_count ?? 0,
            'total_attempts' => $quiz->attempts_count ?? 0,
            'avg_score' => $quiz->attempts()->avg('percent_score') ?? 0,
        ];
    }

    public function render()
    {
        return view('learning::livewire.manage.lesson-quiz-management', [
            'lesson' => $this->lesson,
            'quizzes' => $this->quizzes,
        ]);
    }
}
