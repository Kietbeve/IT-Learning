<?php

namespace Modules\Exam\Livewire\Contributor;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Exam\Models\QuestionOption;
use Modules\Exam\Models\Question;
use Modules\Exam\Models\Question as QuestionModel;
use App\Models\Category;
use WireUi\Traits\WireUiActions;
use Illuminate\Validation\ValidationException;
use Modules\Exam\Http\Requests\StoreQuestionRequest;
use Modules\Exam\Services\ExamService;

class QuestionModal extends Component
{
    use WireUiActions;

    public bool $showViewModal = false;
    public bool $showEditModal = false;
    public bool $showCreateModal = false;
    public bool $showDeleteModal = false;
    public bool $showBulkDeleteModal = false;

    public ?Question $question = null;
    public array $questionIds = [];

    // Form Edit
    public string $content = '';
    public ?string $explanation = null;
    public ?string $difficulty = null;
    public ?string $type = null;
    public ?int $category_id = null;

    public array $options = [];
    public array $categories = [];
    protected ExamService $examService;

    public function boot(ExamService $examService): void
    {
        $this->examService = $examService;
    }

    public function mount(): void
    {
        $this->categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($category) => [
                'id' => $category->id,
                'name' => $category->name,
            ])
            ->all();
    }

    //Nhóm hàm nhận event mở model
    #[On('question-create')]
    public function create(): void
    {
        $this->resetModal();

        $this->options = [
            ['content' => '', 'is_correct' => false],
            ['content' => '', 'is_correct' => false],
            ['content' => '', 'is_correct' => false],
            ['content' => '', 'is_correct' => false],
        ];

        $this->difficulty = 'medium';
        $this->type = 'single_choice';
        $this->showCreateModal = true;
    }

    #[On('question-view')]
    public function view(int $id): void
    {
        $this->resetModal();

        $this->question = Question::with([
            'author',
            'category',
            'options',
        ])->findOrFail($id);

        $this->showViewModal = true;
    }

    #[On('question-edit')]
    public function edit(int $id): void
    {
        $this->resetModal();

        $this->question = Question::with('options')->findOrFail($id);

        $this->category_id = $this->question->category_id;
        $this->content = $this->question->content;
        $this->explanation = $this->question->explanation;
        $this->difficulty = $this->question->difficulty;
        $this->type = $this->question->type;

        // Load existing options
        $existingOptions = $this->question->options->map(function ($option) {
            return [
                'content' => $option->content,
                'is_correct' => $option->is_correct,
            ];
        })->toArray();

        // Ensure at least 4 option slots
        $this->options = array_pad($existingOptions, 4, [
            'content' => '',
            'is_correct' => false,
        ]);

        $this->showEditModal = true;
    }

    #[On('question-delete-confirm')]
    public function deleteConfirm(int $id): void
    {
        $this->resetModal();
        $this->question = Question::findOrFail($id);
        $this->showDeleteModal = true;
    }

    #[On('question-bulk-delete-confirm')]
    public function bulkDeleteConfirm(array $ids): void
    {
        $this->resetModal();
        $this->questionIds = $ids;
        $this->showBulkDeleteModal = true;
    }

    //Nhóm hàm xủ lí 
    public function bulk_delete(): void//Hàm xóa ko cần tách sang service
    {
        if (empty($this->questionIds)) {
            return;
        }

        $deletedCount = Question::whereIn('id', $this->questionIds)
            ->delete();

        $this->showBulkDeleteModal = false;
        $this->questionIds = [];

        $this->notification()->success(
            title: 'Thành công!',
            description: "Đã xóa {$deletedCount} câu hỏi."
        );

        $this->dispatch('question-deleted');
        $this->dispatch('pg:eventRefresh-question-table');
    }

    public function delete_one(): void//Hàm xóa ko cần tách sang service
    {
        if (! $this->question) {
            return;
        }

        $this->question->delete();

        $this->showDeleteModal = false;

        $this->notification()->success(
            title: 'Thành công!',
            description: "Đã xóa 1 câu hỏi."
        );

        $this->dispatch('question-deleted');
        $this->dispatch('pg:eventRefresh-question-table');
    }

    protected function validateOptions(): void//hàm check option
    {
        if ($this->type === 'essay') {
            return;
        }

        $options = collect($this->options)
            ->filter(fn ($option) => filled($option['content'] ?? null))
            ->values();

        $correctCount = $options->where('is_correct', true)->count();

        if ($options->count() < 2) {
            throw ValidationException::withMessages([
                'options' => 'Cần ít nhất 2 đáp án.',
            ]);
        }

        if ($this->type === 'single_choice' && $correctCount !== 1) {
            throw ValidationException::withMessages([
                'options' => 'Phải có đúng 1 đáp án đúng.',
            ]);
        }

        if ($this->type === 'multiple_choice' && $correctCount < 1) {
            throw ValidationException::withMessages([
                'options' => 'Phải có ít nhất 1 đáp án đúng.',
            ]);
        }
    }
    
    public function update(): void
    {
        if (! $this->question) {
            return;
        }

        $validated = $this->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'content' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'type' => ['required', 'in:single_choice,multiple_choice,essay'],
            'options' => ['array'],
            'options.*.content' => ['nullable', 'string'],
            'options.*.is_correct' => ['nullable', 'boolean'],
        ]);

        // Validate options for choice questions
        if (in_array($this->type, ['single_choice', 'multiple_choice'])) {
            $this->validateOptions();
        }

        // Use service layer to update
        $this->examService->updateQuestion($this->question, $validated);

        $this->showEditModal = false;

        $this->notification()->success(
            title: 'Thành công!',
            description: 'Đã cập nhật câu hỏi.'
        );

        $this->dispatch('question-updated');
        $this->dispatch('pg:eventRefresh-question-table');
    }

    public function createQuestion(): void
    {
        $validated = $this->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'content' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'type' => ['required', 'in:single_choice,multiple_choice,essay'],
            'options' => ['array'],
            'options.*.content' => ['nullable', 'string'],
            'options.*.is_correct' => ['nullable', 'boolean'],
        ]);

        // Validate options for choice questions
        if (in_array($this->type, ['single_choice', 'multiple_choice'])) {
            $this->validateOptions();
        }

        $this->examService->createQuestion($validated, Auth::id());

        $this->showCreateModal = false;
        $this->reset(['category_id', 'content', 'explanation', 'difficulty', 'type', 'options']);

        $this->notification()->success(
            title: 'Thành công!',
            description: 'Đã lưu câu hỏi mới.'
        );

        $this->dispatch('question-created');
        $this->dispatch('pg:eventRefresh-question-table');
    }

    public function resetModal(): void
    {
        $this->reset([
            'showViewModal',
            'showEditModal',
            'showCreateModal',
            'question',
            'content',
            'difficulty',
            'type',
            'category_id',
            'options',
        ]);
    }

    public function render()
    {
        return view('exam::contributor.livewire.question-modals');
    }
}
