<?php

namespace Modules\Exam\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Exam\Models\Exam;
use WireUi\Traits\WireUiActions;

class ExamReviewModal extends Component
{
    use WireUiActions;

    public bool $showModal = false;

    public ?Exam $exam = null;

    public string $action = '';

    public string $rejectedReason = '';

    #[On('exam-review-modal')]
    public function open(int $id, string $action): void
    {
        $this->reset();

        $this->exam = Exam::findOrFail($id);

        $this->action = $action;

        $this->showModal = true;
    }

    public function approve(): void
    {
        $this->exam->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejected_reason' => null,
        ]);

        $this->notification()->success(//tạm thời chỉ thông báo
            title: 'Thành công',
            description: 'Đề thi đã được duyệt.'
        );

        $this->close();

        $this->dispatch('pg:eventRefresh-exam-review-table');
    }

    public function reject(): void
    {
        $this->validate([
            'rejectedReason' => [
                'required',
                'string',
                'min:10',
            ],
        ]);

        $this->exam->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejected_reason' => $this->rejectedReason,
        ]);

        
        $this->notification()->success(
            title: 'Thành công',
            description: 'Đề thi đã bị từ chối.'
        );

        $this->close();

        $this->dispatch('pg:eventRefresh-exam-review-table');

        
    }

    public function close(): void
    {
        $this->reset();
    }

    public function render()
    {
        return view(
            'exam::admin.livewire.exam-review-modal'
        );
    }
}