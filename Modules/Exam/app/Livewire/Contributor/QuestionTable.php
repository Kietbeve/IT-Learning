<?php

namespace Modules\Exam\Livewire\Contributor;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Modules\Exam\Models\Question;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class QuestionTable extends PowerGridComponent
{
    public string $tableName = 'question-table';

    public bool $showViewModal = false;

    public bool $showEditModal = false;

    public int $selectedQuestionId = 0;

    /*
    |--------------------------------------------------------------------------
    | Setup
    |--------------------------------------------------------------------------
    */

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 25, 50, 100,200,500])
                ->showRecordCount(),
        ];
    }
    public function header(): array
    {
        return [
            Button::add('create')
            ->slot('➕ Thêm mới')
            ->class(
                'inline-flex items-center gap-2
                px-4 py-2
                rounded-lg
                bg-blue-600 text-white font-medium
                shadow-sm
                transition-all duration-200
                hover:bg-blue-700 hover:shadow-md
                active:scale-95
                focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2'
            )
            ->dispatch('open-create-modal',[]),

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
                    hover:bg-red-700 hover:shadow-md
                    active:scale-95'
                )
                ->dispatch('open-bulk-delete-confirm', []),
        ];
    }
    /*
    |--------------------------------------------------------------------------
    | Datasource
    |--------------------------------------------------------------------------
    */

    public function datasource(): Builder
    {
        return Question::query();
    }

    /*
    |--------------------------------------------------------------------------
    | Fields
    |--------------------------------------------------------------------------
    */

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('content')
            ->add('content_excerpt', fn (Question $model) => Str::limit($model->content, 80))
            ->add('type')
            ->add('type_label', fn (Question $model) => $this->mapQuestionType($model->type))
            ->add('difficulty')
            ->add('difficulty_label', fn (Question $model) => $this->mapDifficulty($model->difficulty));
            // ->add('created_at')
            // ->add('created_at_formatted', fn (Question $model) => $model->created_at?->format('d/m/Y H:i') ?? '—');
    }

    /*
    |--------------------------------------------------------------------------
    | Columns
    |--------------------------------------------------------------------------
    */

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Nội dung câu hỏi', 'content_excerpt', 'content')
                ->searchable(),

            Column::make('Loại câu hỏi', 'type_label', 'type'),

            Column::make('Độ khó', 'difficulty_label', 'difficulty')
                ->sortable(),

            // Column::make('Ngày tạo', 'created_at_formatted', 'created_at')
            //     ->sortable(),

            Column::action('Thao tác'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    public function filters(): array
    {
        return [
            Filter::select('type_label', 'type')
                ->dataSource([
                    ['id' => 'single_choice',   'name' => 'Trắc nghiệm một đáp án'],
                    ['id' => 'multiple_choice',  'name' => 'Trắc nghiệm nhiều đáp án'],
                    ['id' => 'essay',            'name' => 'Tự luận'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('difficulty_label', 'difficulty')
                ->dataSource([
                    ['id' => 'easy',   'name' => 'Dễ'],
                    ['id' => 'medium', 'name' => 'Trung bình'],
                    ['id' => 'hard',   'name' => 'Khó'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function actions(Question $row): array
    {
        return [
            Button::add('view')
                ->slot('Xem')
                ->class('inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700')
                ->dispatch('open-view-modal', ['questionId' => $row->id]),

            Button::add('edit')
                ->slot('Cập nhật')
                ->class('inline-flex items-center rounded-md bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600')
                ->dispatch('open-edit-modal', ['questionId' => $row->id]),

            Button::add('delete')
                ->slot('Xóa')
                ->class('inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700')
                ->dispatch('open-delete-confirm', ['questionId' => $row->id]),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Event Listeners
    |--------------------------------------------------------------------------
    */

    #[On('open-view-modal')]
    public function openViewModal(int $questionId): void
    {
        $this->dispatch('question-view', id: $questionId)
            ->to(QuestionModal::class);
    }

    #[On('open-edit-modal')]
    public function openEditModal(int $questionId): void
    {
        $this->dispatch('question-edit', id: $questionId)
        ->to(QuestionModal::class);
    }

    #[On('open-create-modal')]
    public function openCreateModal(): void
    {
        $this->dispatch('question-create')
        ->to(QuestionModal::class);
    }

    #[On('open-delete-confirm')]
    public function openDeleteConfirm(int $questionId): void
    {
        $this->dispatch('question-delete-confirm', id: $questionId)
        ->to(QuestionModal::class);
    }

    #[On('open-bulk-delete-confirm')]
    public function openBulkDeleteConfirm(): void
    {
        $questionIds = $this->checkboxValues;
        $this->dispatch('question-bulk-delete-confirm', ids: $questionIds)
        ->to(QuestionModal::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Mapping Helpers
    |--------------------------------------------------------------------------
    */

    private function mapQuestionType(string $type): string
    {
        return match ($type) {
            'single_choice'  => 'Trắc nghiệm một đáp án',
            'multiple_choice' => 'Trắc nghiệm nhiều đáp án',
            'essay'          => 'Tự luận',
            default          => $type,
        };
    }

    private function mapDifficulty(string $difficulty): string
    {
        return match ($difficulty) {
            'easy'   => 'Dễ',
            'medium' => 'Trung bình',
            'hard'   => 'Khó',
            default  => $difficulty,
        };
    }
}
