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
use Livewire\WithFileUploads;

class QuestionModal extends Component
{
    use WireUiActions;
    use WithFileUploads;

    public bool $showViewModal = false;
    public bool $showEditModal = false;
    public bool $showCreateModal = false;
    public bool $showDeleteModal = false;
    public bool $showBulkDeleteModal = false;
    public bool $showImportModal = false;
    public bool $showApproveModal = false;
    public bool $showRejectModal = false;
    public string $rejectReason = '';
    public $importFile;

    public ?Question $question = null;
    public array $questionIds = [];

    // Form Edit
    public string $content = '';
    public ?string $explanation = null;
    public ?string $difficulty = null;
    public ?string $type = null;
    public ?int $category_id = null;

    public ?string $answer_text=null;

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

        // Khởi tạo với 2 options (tối thiểu yêu cầu)
        $this->options = [
            ['content' => '', 'is_correct' => false],
            ['content' => '', 'is_correct' => false],
        ];
        $this->answer_text = null;

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
        $this->answer_text = $this->question->answer_text;

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

    #[On('question-approve')]
    public function approveConfirm(int $id): void
    {
        $this->resetModal();

        $this->question = Question::findOrFail($id);

        $this->showApproveModal = true;
    }

    #[On('question-reject')]
    public function rejectConfirm(int $id): void
    {
        $this->resetModal();

        $this->question = Question::findOrFail($id);

        $this->rejectReason = '';

        $this->showRejectModal = true;
    }

    //Nhóm hàm quản lý options động
    public function addOption(): void
    {
        // Thêm option mới vào cuối mảng
        $this->options[] = ['content' => '', 'is_correct' => false];
    }

    public function removeOption(int $index): void
    {
        // Kiểm tra tối thiểu 2 options
        if (count($this->options) <= 2) {
            $this->notification()->error(
                title: 'Không thể xóa',
                description: 'Phải có ít nhất 2 đáp án.'
            );
            return;
        }

        // Xóa và reindex lại mảng
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function selectSingleCorrectAnswer(int $index): void
    {
        // Bỏ chọn tất cả, chỉ chọn đáp án được click (cho single choice)
        foreach ($this->options as $i => $option) {
            $this->options[$i]['is_correct'] = ($i === $index);
        }
    }

    public function updatedType($value): void
    {
        // Reset options khi chuyển sang/từ essay
        if ($value === 'essay') {
            $this->options = [];
        } elseif (empty($this->options)) {
            // Khôi phục 2 options mặc định khi chuyển từ essay sang choice
            $this->options = [
                ['content' => '', 'is_correct' => false],
                ['content' => '', 'is_correct' => false],
            ];
        }
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

        //Check trùng lặp lựa chọn
        $duplicate = $options
            ->pluck('content')
            ->duplicates();

                if ($duplicate->isNotEmpty()) {
                    throw ValidationException::withMessages([
                        'options' => 'Các đáp án không được trùng nhau.',
                    ]);
                }

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
            'answer_text' => [
                $this->type === 'essay' ? 'required' : 'nullable',
                'string',
            ],
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
            'content' => ['required',  function ($attribute, $value, $fail) {
                $text = strip_tags($value);
                $text = html_entity_decode($text);
                $text = str_replace("\xC2\xA0", ' ', $text);
                $text = preg_replace('/\s+/u', ' ', $text);

                if (trim($text) === '') {
                    $fail('Nội dung câu hỏi không được để trống.');
                }
            },],
            'explanation' => ['nullable', 'string'],
            'difficulty' => ['required', 'in:easy,medium,hard'],
            'type' => ['required', 'in:single_choice,multiple_choice,essay'],
            'options' => ['array'],
            'options.*.content' => ['nullable', 'string'],
            'options.*.is_correct' => ['nullable', 'boolean'],
            'answer_text' => [
                $this->type === 'essay' ? 'required' : 'nullable',
                'string',
            ],
        ]);

        // Kiểm tra nội dung câu hỏi trùng lặp
        $content = $this->normalizeContent($validated['content']);
        $exists = Question::where('author_id', Auth::id())
            ->get()
            ->contains(fn ($question) =>
                $this->normalizeContent($question->content) === $content
            );

        if ($exists) {
            throw ValidationException::withMessages([
                'content' => 'Bạn đã có một câu hỏi với nội dung này.',
            ]);
        }

        // Validate options for choice questions
        if (in_array($this->type, ['single_choice', 'multiple_choice'])) {
            $this->validateOptions();
        }

        $this->examService->createQuestion(
            [...$validated,'is_shared' => ! auth()->user()->hasRole('contributor'),],
            Auth::id()
        );

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
            'answer_text', // Reset đáp án tự luận
        ]);
    }
    public function approve(): void
    {
        if (! $this->question) {
            return;
        }

        $this->question->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejected_reason' => null,
        ]);

        $this->showApproveModal = false;

        $this->notification()->success(
            title: 'Thành công!',
            description: 'Đã duyệt câu hỏi.'
        );

        $this->dispatch('pg:eventRefresh-question-table');
    }
    public function reject(): void
    {
        if (! $this->question) {
            return;
        }

        $this->validate([
            'rejectReason' => ['required', 'string', 'max:500'],
        ]);

        $this->question->update([
            'status' => 'rejected',
            'rejected_reason' => $this->rejectReason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $this->showRejectModal = false;

        $this->notification()->success(
            title: 'Thành công!',
            description: 'Đã từ chối câu hỏi.'
        );

        $this->dispatch('pg:eventRefresh-question-table');
    }
    
    //import file
    #[On('question-import')]
    public function import(): void
    {
        $this->resetValidation();

        $this->importFile = null;

        $this->showImportModal = true;
    }

    public function importQuestions(): void
    {
        
        $this->validate([
            'importFile' => [
                'required',
                'file',
                'mimes:xlsx,xls,csv',
            ],
        ]);

        // $count = $this->examService->importQuestions(
        //     $this->importFile,
        //     auth()->id()
        // );
        try {

            $count = $this->examService->importQuestions(
                $this->importFile,
                auth()->id()
            );

        } catch (\Throwable $e) {
            $this->notification()->error(
                title: 'Import thất bại',
                description: $e->getMessage()
            );
            return;
        }
        
        $this->showImportModal = false;

        $this->importFile = null;

        $this->notification()->success(
            title: 'Thành công',
            description: "Đã import {$count} câu hỏi."
        );

        $this->dispatch('pg:eventRefresh-question-table');
    }

    //Hàm check nội dung trùng lặp
    private function normalizeContent(string $content): string
    {
         // 1. Bỏ HTML
        $content = strip_tags($content);

        // 2. Decode HTML Entity
        $content = html_entity_decode($content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 3. Unicode Normalize (NFC)
        if (class_exists(\Normalizer::class)) {
            $content = \Normalizer::normalize($content, \Normalizer::FORM_C);
        }

        // 4. Chuyển về lowercase
        $content = mb_strtolower($content, 'UTF-8');

        // 5. Bỏ dấu tiếng Việt
        $content = \Str::ascii($content);

        // 6. Bỏ dấu câu, chỉ giữ chữ, số và khoảng trắng
        $content = preg_replace('/[^a-z0-9\s]/', ' ', $content);

        // 7. Gom nhiều khoảng trắng thành 1
        $content = preg_replace('/\s+/', ' ', $content);

        return trim($content);
    }

    public function render()
    {
        return view('exam::contributor.livewire.question-modals');
    }
}
