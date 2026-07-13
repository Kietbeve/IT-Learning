<?php

namespace Modules\Exam\Livewire\Contributor;

use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Exam\Models\Exam;
use App\Models\Category;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Str;

use Modules\Exam\Services\ExamService;

class ExamModal extends Component
{
    use WireUiActions;

    //inject ExamService
    protected ExamService $examService;
    public function boot(ExamService $examService){
        $this->examService = $examService;
    }
    //Trạng thái mở modal
    public bool $showViewModal = false;
    public bool $showEditModal = false;
    public bool $showDeleteModal = false;

    // Exam being viewed (read-only, for view modal)
    public ?Exam $exam = null;

    // Exam ID being edited
    public ?int $editingExamId = null;

    // Form fields for edit modal
    public string $title = '';
    // public ?string $slug = null;
    public ?string $short_description = null;
    public ?string $description = null;
    public string $type = 'multiple_choice';
    public string $mode = 'practice';
    public int $duration_minutes = 30;
    public string $pass_percent = '50';
    public string $visibility = 'public';
    public string $status = 'pending';
    public ?string $publish_at = null;
    public ?int $category_id = null;

    // Danh sách danh mục cho dropdown
    public array $categories = [];

    public function mount(): void
    {
        $this->categories = Category::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])
            ->all();
    }

    #[On('exam-create')]
    public function create(): void
    {
        $this->resetModal();
        $this->showEditModal = true;
    }

    #[On('exam-view')]
    public function view(int $id): void
    {
        $this->resetModal();

        $this->exam = Exam::withCount('questions')->with([
            'author',
            'category',
            'reviewer',
        ])->findOrFail($id);

        $this->showViewModal = true;
    }

    #[On('exam-edit')]
    public function edit(int $id): void
    {
        $this->resetModal();

        $exam = Exam::findOrFail($id);

        $this->editingExamId     = $exam->id;
        $this->title             = $exam->title ?? '';
        // $this->slug              = $exam->slug;
        $this->short_description = $exam->short_description;
        $this->description       = $exam->description;
        $this->type              = $exam->type ?? 'multiple_choice';
        $this->mode              = $exam->mode ?? 'practice';
        $this->duration_minutes  = $exam->duration_minutes ?? 30;
        $this->pass_percent      = (string) ($exam->pass_percent ?? '50');
        $this->visibility        = $exam->visibility ?? 'public';
        $this->status            = $exam->status ?? 'draft';
        $this->publish_at        = $exam->publish_at?->format('Y-m-d\TH:i');
        $this->category_id       = $exam->category_id;

        $this->showEditModal = true;
    }

    #[On('exam-delete')]
    public function deleteConfirm(int $id): void
    {
        $this->resetModal();
        $this->exam = Exam::findOrFail($id);
        $this->showDeleteModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title'             => ['required', 'string', 'max:255'],
            // 'slug'              => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description'       => ['nullable', 'string'],
            'type'              => ['required', 'in:multiple_choice,essay,hybrid'],
            'mode'              => ['required', 'in:practice,official'],
            'duration_minutes'  => ['required', 'integer', 'min:1'],
            'pass_percent'      => ['required', 'numeric', 'min:0', 'max:100'],
            'visibility'        => ['required', 'in:public,private'],
            'status'            => ['required', 'in:draft,pending,approved,rejected'],
            'publish_at'        => ['nullable', 'date'],
            'category_id'       => ['required', 'integer', 'exists:categories,id'],
        ]);

<<<<<<< HEAD
        $this->examService->saveExam($validated,$this->editingExamId,auth()->id());
=======
        $exam = $this->editingExamId
            ? Exam::findOrFail($this->editingExamId)
            : new Exam();

        $exam->fill($validated);

        if (! $exam->exists) {
            $exam->slug=Exam::generateUniqueSlug($this->title);
            $exam->author_id = auth()->id();
            $exam->id = (string) Str::uuid();
        }

        $exam->save();
>>>>>>> 9e0f795 (cập nhật dữ liệu)

        $this->showEditModal = false;

        $this->dispatch('pg:eventRefresh-exam-table');

        $this->notification()->success(
            title: 'Thành công',
            description: 'lưu đề thi thành công'
        );
    }

    public function delete_one(): void
    {

        if ($this->exam->questions()->exists()) {
            $this->showDeleteModal = false;
            $this->notification()->error(
                title: 'Không thể xóa',
                description: 'Đề thi vẫn còn câu hỏi liên kết.'
            );

            return;
        }

        if ($this->exam->attempts()->exists()) {
            $this->showDeleteModal = false;
            $this->notification()->error(
                title: 'Không thể xóa',
                description: 'Đề thi đã có lượt làm bài.'
            );

            return;
        }

        $this->exam->delete();

        $this->dispatch('pg:eventRefresh-exam-table');

        $this->showDeleteModal = false;

        $this->notification()->success(
            title: 'Thành công',
            description: 'Đã xóa đề thi.'
        );
    }

    public function resetModal(): void
    {
        $this->reset([
            'showViewModal',
            'showEditModal',
            'showDeleteModal',
            'exam',
            'editingExamId',
            'title',
            // 'slug',
            'short_description',
            'description',
            'type',
            'mode',
            'duration_minutes',
            'pass_percent',
            'visibility',
            'status',
            'publish_at',
            'category_id',
        ]);
    }

    public function render()
    {
        return view('exam::contributor.livewire.exam-modals');
    }
}
