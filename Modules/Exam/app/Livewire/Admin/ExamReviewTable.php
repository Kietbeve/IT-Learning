<?php

namespace Modules\Exam\Livewire\Admin;

use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Modules\Exam\Models\Exam;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

class ExamReviewTable extends PowerGridComponent
{
    public string $tableName = 'exam-review-table';
    public ?int $selectedExamId = null;

    public string $rejectedReason = '';

    public string $status = 'pending';

     public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showToggleColumns()
                ->showSearchInput(),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('pending')
                ->slot('Pending ('.$this->pendingCount().')')
                ->class($this->status === 'pending'
                    ? 'bg-yellow-500 text-white px-3 py-2 rounded'
                    : 'bg-gray-100 px-3 py-2 rounded')
                ->dispatch('review-status', ['status' => 'pending']),

            Button::add('approved')
                ->slot('Approved ('.$this->approvedCount().')')
                ->class($this->status === 'approved'
                    ? 'bg-green-500 text-white px-3 py-2 rounded'
                    : 'bg-gray-100 px-3 py-2 rounded')
                ->dispatch('review-status', ['status' => 'approved']),

            Button::add('rejected')
                ->slot('Rejected ('.$this->rejectedCount().')')
                ->class($this->status === 'rejected'
                    ? 'bg-red-500 text-white px-3 py-2 rounded'
                    : 'bg-gray-100 px-3 py-2 rounded')
                ->dispatch('review-status', ['status' => 'rejected']),
        ];
    }

    public function datasource(): Builder
    {
        return Exam::query()
                ->with(['author'])
                ->withCount('questions')
                ->where('status', $this->status)
                ->oldest('updated_at')
                ->oldest('created_at');
                
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()

            ->add('id')

            ->add('title')

            ->add(
                'author_name',
                fn (Exam $exam) => $exam->author?->name
            )

            ->add('questions_count')

            ->add(
                'waiting_days',
                //fn (Exam $exam) => $exam->created_at->diffInDays(now())
                fn (Exam $exam) => optional($exam->updated_at ?? $exam->created_at)->diffForHumans()
            )

            ->add(
                'created_at_formatted',
                fn (Exam $exam) => $exam->created_at->format('d/m/Y H:i')
            );
    }

    public function columns(): array
    {
        return [

            Column::make('Id', 'id')
                ->sortable(),

            Column::make('Tên đề', 'title')
                ->searchable(),

            Column::make('Tác giả', 'author_name'),
                // ->searchable(),

            Column::make('Số câu', 'questions_count')
                ->sortable(),

            Column::make('Chờ duyệt', 'waiting_days'),
                // ->sortable(),

            Column::make('Ngày tạo', 'created_at_formatted'),

            Column::action('Thao tác'),
        ];
    }

    public function filters(): array
    {
        return [

            // Filter::inputText('title'),

        ];
    }

    public function actions(Exam $row): array
    {
        if($row->status=="pending"){
            return [

                Button::add('review')
                    ->slot('👁️')
                    ->route(
                        'admin.review.exam.detail',
                        ['exam' => $row->id]
                    ),

                Button::add('approve')
                    ->slot('✓')
                    ->class(
                        'pg-btn pg-btn-success'
                    )
                    ->dispatch(
                        'exam-review-modal',
                        ['id' => $row->id,'action'=> 'approve']
                    ),

                Button::add('reject')
                    ->slot('✗')
                    ->class(
                        'pg-btn pg-btn-danger'
                    )
                    ->dispatch(
                        'exam-review-modal',
                        ['id' => $row->id,'action'=> 'reject']
                    ),
            ];
        }
        return [

            Button::add('review')
                ->slot('👁️')
                ->route(
                    'admin.review.exam.detail',
                    ['exam' => $row->id]
                ),
        ];
        
    }

    #[On('review-status')]
    public function changeStatus(string $status): void
    {
        $this->status = $status;

        $this->resetPage();
    }

    #[On('approve-exam')]
    public function approveExam(array $payload): void
    {
        $exam = Exam::findOrFail(
            $payload['id']
        );

        $exam->update([
            'status' => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejected_reason' => null,
        ]);

        $this->dispatch('pg:eventRefresh-default');
    }

    #[On('reject-exam-modal')]
    public function openRejectModal(array $payload): void
    {
        $this->selectedExamId = $payload['id'];

        $this->dispatch(
            'open-reject-exam-modal'
        );
    }

    public function pendingCount(): int
    {
        return Exam::where('status', 'pending')->count();
    }

    public function approvedCount(): int
    {
        return Exam::where('status', 'approved')->count();
    }

    public function rejectedCount(): int
    {
        return Exam::where('status', 'rejected')->count();
    }

    public function getStatsProperty(): array
    {
        return [
            'pending' => Exam::where('status', 'pending')->count(),
            'approved' => Exam::where('status', 'approved')->count(),
            'rejected' => Exam::where('status', 'rejected')->count(),
        ];
    }
}