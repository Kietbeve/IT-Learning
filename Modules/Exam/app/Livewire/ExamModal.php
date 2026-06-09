<?php

namespace Modules\Exam\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Exam\Models\Exam;
use App\Models\Category;
use WireUi\Traits\WireUiActions;

class ExamModal extends Component
{
    use WireUiActions;

    public bool $showViewModal = false;
    public bool $showEditModal = false;
    public bool $showCreateModal = false;

    public ?Exam $exam = null;

    #[On('exam-create')]
    public function create(): void
    {
        $this->resetModal();
        $this->showCreateModal = true;
    }

    #[On('exam-view')]
    public function view(int $id): void
    {
        $this->resetModal();

        $this->exam = Exam::with([
            'author',
            'category',
        ])->findOrFail($id);

        $this->showViewModal = true;
    }

    #[On('exam-edit')]
    public function edit(int $id): void
    {
        $this->resetModal();

        $this->exam = Exam::with([
            'category',
        ])->findOrFail($id);

        $this->showEditModal = true;
    }

    public function resetModal(): void
    {
        $this->reset([
            'showViewModal',
            'showEditModal',
            'showCreateModal',
            'exam',
        ]);
    }

    public function render()
    {
        return view('exam::livewire.partials.exam-modals');
    }
}
