<?php

namespace Modules\Exam\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Exam\Models\QuestionOption;
use Modules\Exam\Models\Question;
use Modules\Exam\Models\Question as QuestionModel;
use App\Models\Category;
use WireUi\Traits\WireUiActions;

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
    public ?string $difficulty = null;
    public ?string $type = null;
    public ?int $category_id = null;

    public array $options = [];
    public array $categories = [];

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

        $this->question = Question::findOrFail($id);

        $this->content = $this->question->content;
        $this->difficulty = $this->question->difficulty;
        $this->type = $this->question->type;

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
    public function bulk_delete(): void
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
    }
    public function delete_one(): void
    {
        if (! $this->question) {
            return;
        }

        $this->question->delete();

        $this->showDeleteModal = false;

        $this->dispatch('question-deleted');
    }
    
    public function update(): void
    {
        if (! $this->question) {
            return;
        }

        $validated = $this->validate([
            'content' => ['required'],
            'difficulty' => ['required'],
            'type' => ['required'],
        ]);

        $this->question->update($validated);

        $this->showEditModal = false;

        $this->dispatch('question-updated');
    }

    public function createQuestion(): void
    {
        $validated = $this->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'content' => ['required', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'type' => ['required', 'in:single_choice,multiple_choice,essay'],
            'options' => ['array'],
            'options.*.content' => ['nullable', 'string'],
            'options.*.is_correct' => ['nullable', 'boolean'],
        ]);

        $userId = Auth::id();

        DB::transaction(function () use ($validated, $userId) {
            $question = Question::create([
                'author_id' => $userId,
                'category_id' => $validated['category_id'],
                'content' => $validated['content'],
                'difficulty' => $validated['difficulty'],
                'status' => 'pending',
                'type' => $validated['type'],
            ]);

            if (in_array($validated['type'], ['single_choice', 'multiple_choice'], true)) {
                collect($validated['options'] ?? [])
                    ->filter(fn ($option) => filled($option['content'] ?? null))
                    ->values()
                    ->each(function (array $option, int $index) use ($question): void {
                        QuestionOption::create([
                            'question_id' => $question->id,
                            'option_key' => chr(65 + $index),
                            'content' => $option['content'],
                            'is_correct' => (bool) ($option['is_correct'] ?? false),
                            'sort_order' => $index + 1,
                        ]);
                    });
            }
        });

        $this->showCreateModal = false;
        $this->reset(['category_id', 'content', 'difficulty', 'type', 'options']);

        $this->notification()->success(
            title: 'Thành công!',
            description: 'Đã lưu câu hỏi mới.'
        );

        $this->dispatch('question-created');
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
        return view('exam::livewire.partials.question-modals');
    }
}
