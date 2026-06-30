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
                'inline-flex items-center gap-2 px-4 py-2.5 rounded-xl
                text-sm font-semibold text-white bg-blue-600
                shadow-md shadow-blue-500/20 transition-all duration-200 transform
                hover:bg-blue-700 hover:shadow-lg hover:shadow-blue-500/30 hover:-translate-y-0.5
                active:translate-y-0 active:scale-[0.98]
                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
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
                'inline-flex items-center gap-2 px-4 py-2.5 rounded-xl
                text-sm font-semibold text-white bg-red-600
                shadow-md shadow-red-500/20 transition-all duration-200 transform
                hover:bg-red-700 hover:shadow-lg hover:shadow-red-500/30 hover:-translate-y-0.5
                active:translate-y-0 active:scale-[0.98]
                focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2'
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
        ->add('content_preview', function ($question) {
            // Strip HTML tags and truncate to 100 characters
            $plainText = strip_tags($question->content);
            return str($plainText)->limit(100);
        })
        ->add('type')
        ->add('type_label', fn ($question) => match ($question->type) {
            'single_choice'   => 'Một đáp án',
            'multiple_choice' => 'Nhiều đáp án',
            'essay'           => 'Tự luận',
            default           => $question->type,
        })
        ->add('difficulty')
        ->add('difficulty_label', function ($question) {
            return match ($question->difficulty) {
                'easy' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Dễ</span>',
                'medium' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Trung bình</span>',
                'hard' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Khó</span>',
                default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">'
                    . e($question->difficulty) .
                '</span>',
            };
        })
        ->add('score')
        ->add('sort_order');
        // ->add('status');
    }

    public function columns(): array
    {
        return [
            // Column::make('ID', 'id')
            //     ->sortable(),
            Column::make('Thứ tự', 'sort_order')
                ->sortable(),

            Column::make('Câu hỏi', 'content_preview', 'content')
                ->searchable(),

            Column::make('Loại câu hỏi', 'type_label', 'type'),

            Column::make('Độ khó', 'difficulty_label', 'difficulty'),

            Column::make('Điểm', 'score')
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

            Filter::select('difficulty', 'difficulty')
                ->dataSource([
                    ['difficulty' => 'easy', 'label' => 'Dễ'],
                    ['difficulty' => 'medium', 'label' => 'Trung bình'],
                    ['difficulty' => 'hard', 'label' => 'Khó'],
                ])
                ->optionValue('difficulty')
                ->optionLabel('label'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('view-question')
                ->slot('👁️ Xem')
                ->class(
                'inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                text-xs font-semibold text-white bg-indigo-600
                shadow-sm shadow-indigo-500/20 transition-all duration-200 transform
                hover:bg-indigo-700 hover:shadow-md hover:shadow-indigo-500/30 hover:-translate-y-0.5
                active:translate-y-0 active:scale-[0.96]
                focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1'
                )
                ->dispatch('open-view-question-modal', ['questionId' => $row->id]),
            
            Button::add('remove')
                ->slot('❌ Gỡ')
                ->class(
                'inline-flex items-center gap-1 px-3 py-1.5 rounded-lg
                text-xs font-semibold text-white bg-red-600
                shadow-sm shadow-red-500/20 transition-all duration-200 transform
                hover:bg-red-700 hover:shadow-md hover:shadow-red-500/30 hover:-translate-y-0.5
                active:translate-y-0 active:scale-[0.96]
                focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-1'
                )
                ->dispatch('remove-question', ['id' => $row->id]),
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