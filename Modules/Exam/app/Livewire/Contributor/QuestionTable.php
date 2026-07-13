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
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use WireUi\Traits\WireUiActions;

final class QuestionTable extends PowerGridComponent
{
    use WireUiActions;
    public string $tableName = 'question-table';

    public bool $showViewModal = false;

    public bool $showEditModal = false;

    public int $selectedQuestionId = 0;

    public bool $isAdmin = false;//check xem có phải admin không
    /*
    |--------------------------------------------------------------------------
    | Setup
    |--------------------------------------------------------------------------
    */

    public function setUp(): array
    {
        $this->showCheckBox();
        $this->isAdmin = !auth()->user()->hasRole('contributor');


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
             ->slot('➕ Thêm')
            ->class('rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700')
            ->dispatch('question-create',[]),

            Button::add('import')
            ->slot('📥 Import')
            ->class('rounded-lg bg-emerald-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-emerald-700')
            ->dispatch('question-import', []),

            Button::add('bulk-delete')
                ->slot(
                '🗑️ Xóa (<span x-text="window.pgBulkActions.count(\''.$this->tableName.'\')"></span>)'
                )
                ->class('rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700')
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
        // return Question::query()
        // ->where('author_id', auth()->id());//Chỉ quản lí câu hỏi cá nhân
        return Question::query()
        ->when(
            !$this->isAdmin,// Nếu là Contributor thì chỉ quản lí câu hỏi của mình
            fn ($query) => $query->where('author_id', auth()->id())
        );
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
            ->add('content_excerpt', fn (Question $model) => Str::words(strip_tags(html_entity_decode($model->content)), 4, '...'))
            ->add('type')
            ->add('type_label', fn (Question $model) => $this->mapQuestionType($model->type))
            ->add('difficulty')
            ->add('difficulty_label', fn (Question $model) => $this->mapDifficulty($model->difficulty))
            ->add('status_badge', function (Question $model) {
                return match ($model->status) {
                    'pending' => '<span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-medium text-yellow-800">
                                    Chờ duyệt
                                </span>',

                    'approved' => '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-800">
                                    Đã duyệt
                                </span>',

                    'rejected' => '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-800">
                                    Từ chối
                                </span>',

                    default => '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-800">
                                    Không xác định
                                </span>',
                };
            })
            ->add('is_shared')
            ->add('share_button', function (Question $model) {
                if (! $this->isAdmin) {
                    return $model->is_shared
                        ? '<button class="px-3 py-1 bg-green-500 text-white rounded" disabled>Đã chia sẻ</button>'
                        : '<button class="px-3 py-1 bg-gray-400 text-white rounded cursor-not-allowed" disabled>Chia sẻ</button>';
                }

                    return $model->is_shared
                        ? '<button wire:click="share('.$model->id.')" style="cursor:pointer;" class="px-3 py-1 bg-green-500 text-white rounded">Hủy chia sẻ</button>'
                        : '<button wire:click="share('.$model->id.')" style="cursor:pointer;" class="px-3 py-1 bg-blue-500 text-white rounded">Chia sẻ</button>';
                });
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
            
            Column::make('Trạng thái', 'status_badge', 'status'),
            // Column::make('Ngày tạo', 'created_at_formatted', 'created_at')
            //     ->sortable(),
            Column::make('Chia sẻ', 'share_button'),

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
            
            Filter::boolean('is_shared'),

            Filter::select('status', 'status')
                ->dataSource([
                    ['id' => 'pending', 'name' => 'Chờ duyệt'],
                    ['id' => 'approved', 'name' => 'Đã duyệt'],
                    ['id' => 'rejected', 'name' => 'Từ chối'],
                ])
                ->optionValue('id')
                ->optionLabel('name'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function actions(Question $row): array
    {
        $actions = [];

        $actions[] = Button::add('view')
            ->slot('👁')
            ->tooltip('Xem')
            ->class('rounded-md border border-blue-200 bg-blue-50 p-2 text-blue-600 transition hover:bg-blue-100 hover:border-blue-300')
            ->dispatch('question-view', ['id' => $row->id]);
        //Nếu là admin và câu hỏi trạng thái là chờ duyệt thì sẽ hiện nhóm nút duyệt
        if ($this->isAdmin && $row->status === 'pending') {
            $actions[] = Button::add('approve')
                ->slot('✔')
                ->tooltip('Duyệt')
                ->class('rounded-md border border-green-200 bg-green-50 p-2 text-green-600 transition hover:bg-green-100 hover:border-green-300')
                ->dispatch('question-approve', ['id' => $row->id]);

            $actions[] = Button::add('reject')
                ->slot('✖')
                ->tooltip('Từ chối')
                ->class('rounded-md border border-red-200 bg-red-50 p-2 text-red-600 transition hover:bg-red-100 hover:border-red-300')
                ->dispatch('question-reject', ['id' => $row->id]);

            return $actions;
        }

        $actions[] = Button::add('edit')
            ->slot('✏️')
            ->tooltip('Cập nhật')
            ->class('rounded-md border border-amber-200 bg-amber-50 p-2 text-amber-600 transition hover:bg-amber-100 hover:border-amber-300')
            ->dispatch('question-edit', ['id' => $row->id]);

        $actions[] = Button::add('delete')
           ->slot('🗑')
            ->tooltip('Xóa')
            ->class('rounded-md border border-red-200 bg-red-50 p-2 text-red-600 transition hover:bg-red-100 hover:border-red-300')
            ->dispatch('question-delete-confirm', ['id' => $row->id]);

        return $actions;
    }

    public function actionRules($row): array
    {
        return [
            Rule::rows()
                ->when(fn (Question $q) => $q->status === 'pending')
                ->setAttribute('class', 'bg-yellow-50'),

            Rule::rows()
                ->when(fn (Question $q) => $q->status === 'approved')
                ->setAttribute('class', 'bg-green-50'),

            Rule::rows()
                ->when(fn (Question $q) => $q->status === 'rejected')
                ->setAttribute('class', 'bg-red-50'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Event Listeners
    |--------------------------------------------------------------------------
    */

    // #[On('open-view-modal')]
    // public function openViewModal(int $questionId): void
    // {
    //     $this->dispatch('question-view', id: $questionId)
    //         ->to(QuestionModal::class);
    // }

    // #[On('open-edit-modal')]
    // public function openEditModal(int $questionId): void
    // {
    //     $this->dispatch('question-edit', id: $questionId)
    //     ->to(QuestionModal::class);
    // }

    // #[On('open-delete-confirm')]
    // public function openDeleteConfirm(int $questionId): void
    // {
    //     $this->dispatch('question-delete-confirm', id: $questionId)
    //     ->to(QuestionModal::class);
    // }

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

    public function share($id)
    {
        $question = Question::findOrFail($id);

        $question->update([
            'is_shared' => !$question->is_shared,
        ]);

        $this->notification()->success(
            title: 'Thành công',
            description: $question->is_shared ? 'Đã chia sẻ câu hỏi.' : 'Đã hủy chia sẻ câu hỏi.'
        );

        $this->dispatch('$refresh');
    }
}
