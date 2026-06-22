<?php

namespace Modules\Exam\Livewire\Contributor;

use Illuminate\Database\Eloquent\Builder;
use Modules\Exam\Models\Question;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Button;
use Livewire\Attributes\On;

final class ExamQuestionTable extends PowerGridComponent
{
    public string $tableName = 'exam-question-table';
    // public string $primaryKey = 'questions.id';

    // public string $sortField = 'questions.id';
    public int $examId;
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),

            PowerGrid::footer()
                ->showPerPage(
                    perPage: 10,
                    perPageValues: [10, 25, 50, 100]
                )
                ->showRecordCount(),
        ];
    }
    public function header(): array
    {
        return [
            Button::add('add-question')
                ->slot('➕ Thêm câu hỏi')
                ->class(
                    'inline-flex items-center gap-2
                    px-4 py-2
                    rounded-lg
                    bg-blue-600 text-white font-medium
                    shadow-sm
                    transition-all duration-200
                    hover:bg-blue-700 hover:shadow-md'
                )
                ->dispatch('open-add-question-modal', ['examId' => $this->examId]),

            Button::add('bulk-delete')
                ->slot(
                    '🗑️ Xóa (
                    <span class="font-bold"
                        x-text="window.pgBulkActions.count(\'' . $this->tableName . '\')">
                    </span>)'
                )
                ->class(
                    'inline-flex items-center gap-2
                    rounded-lg
                    bg-red-600
                    px-4 py-2
                    text-sm font-semibold text-white
                    shadow-sm
                    transition-all duration-200
                    hover:bg-red-700'
                )
                ->dispatch('open-bulk-remove-confirm', []),
        ];
    }

    public function datasource(): Builder
    {
        return Question::query()
        ->join(
            'exam_questions',
            'questions.id',
            '=',
            'exam_questions.question_id'
        )
        ->where(
            'exam_questions.exam_id',
            $this->examId
        )
        ->select([
            'questions.*',
            'exam_questions.score as score',
            'exam_questions.sort_order as sort_order',
        ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
        ->add('id')
        ->add('content')
        ->add('type')
        ->add('type_label', fn ($question) => match ($question->type) {
            'single_choice'   => 'Một đáp án',
            'multiple_choice' => 'Nhiều đáp án',
            'essay'           => 'Tự luận',
            default           => $question->type,
        })
        ->add('score')
        ->add('sort_order');
        // ->add('status');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Câu hỏi', 'content')
                ->searchable(),

            Column::make('Loại câu hỏi', 'type_label', 'type'),

            Column::make('Điểm', 'score')
                ->sortable(),

            Column::make('Thứ tự', 'sort_order')
                ->sortable(),

            // Column::make('Trạng thái', 'status'),

            // Column::action('Thao tác'),
            Column::action('Thao tác'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('type_label', 'type')
                ->dataSource([
                    [
                        'id' => 'single_choice',
                        'name' => 'Trắc nghiệm một đáp án',
                    ],
                    [
                        'id' => 'multiple_choice',
                        'name' => 'Trắc nghiệm nhiều đáp án',
                    ],
                    [
                        'id' => 'essay',
                        'name' => 'Tự luận',
                    ],
                ])
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('remove')
                ->slot('Gỡ')
                ->class('inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white')
                ->dispatch('remove-question', ['id' => $row->id]),

            Button::add('view-question')
                ->slot('Xem')
                ->class('inline-flex items-center rounded-md bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white')
                ->dispatch('open-view-question-modal', ['questionId' => $row->id]),
        ];
    }

    #[On('questions-added-to-exam')]
    public function refreshTable(): void
    {
        // PowerGrid tự động refresh khi method này được gọi
    }

    #[On('questions-removed-from-exam')]
    public function refreshAfterRemove(): void
    {
        // PowerGrid auto-refresh
    }

    #[On('open-bulk-remove-confirm')]
    public function openBulkRemoveConfirm(): void
    {
        $questionIds = $this->checkboxValues; // PowerGrid selected IDs
        $this->dispatch('bulk-remove-questions', ids: $questionIds)
            ->to(ExamQuestionModal::class);
        // dd(1);
    }

    #[On('open-view-question-modal')]
    public function openViewModal($questionId): void
    {
        $this->dispatch('question-view', id: $questionId)
            ->to(QuestionModal::class);
    }
}