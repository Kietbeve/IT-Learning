<?php

namespace Modules\Exam\Livewire\Contributor;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Modules\Exam\Models\Exam;
use App\Models\Category;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use PowerComponents\LivewirePowerGrid\Facades\Rule;

final class ExamTable extends PowerGridComponent
{
    public string $tableName = 'exam-table';

    public bool $showViewModal = false;

    public bool $showEditModal = false;

    public int $selectedExamId = 0;

    /*
    |--------------------------------------------------------------------------
    | Setup
    |--------------------------------------------------------------------------
    */

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),

            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 25, 50, 100, 200, 500])
                ->showRecordCount(),

            PowerGrid::detail()
                ->view('exam::contributor.partials.exam-check')
                ->showCollapseIcon(),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('create')
                ->slot('➕ Thêm mới')
                ->class($this->buttonClass(
                    'from-blue-500 to-indigo-600 hover:from-blue-400 hover:to-indigo-500 focus:ring-blue-500'
                ))
                ->dispatch('open-create-modal', []),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Datasource
    |--------------------------------------------------------------------------
    */

    public function datasource(): Builder
    {
        return Exam::query()
            ->with('category')
            ->withCount('questions')
            ->where('author_id', auth()->id());
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
            ->add('title')
            ->add('category_name', fn (Exam $model) => $model->category?->name ?? '—')
            ->add('type')
            ->add('type_label', fn (Exam $model) => $this->mapExamType($model->type))
            ->add('mode')
            ->add('mode_label', fn (Exam $model) => $this->mapExamMode($model->mode))
            ->add('duration_minutes')
            ->add('status')
            ->add('status_label', fn (Exam $model) => $this->mapExamStatus($model->status))
            ->add('attempt_count');
            // ->add('created_at')
            // ->add('created_at_formatted', fn (Exam $model) => $model->created_at?->format('d/m/Y H:i') ?? '—');
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

            Column::make('Tiêu đề', 'title')
                ->sortable()
                ->searchable(),

            Column::make('Danh mục', 'category_name', 'category_id'),

            Column::make('Loại đề', 'type_label', 'type'),

            Column::make('Chế độ', 'mode_label', 'mode'),

            Column::make('Thời lượng (phút)', 'duration_minutes')
                ->sortable(),

            Column::make('Trạng thái', 'status_label', 'status'),

            Column::make('Số lượt làm', 'attempt_count')
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
            Filter::select('category_name', 'category_id')
                ->dataSource(Category::query()->orderBy('name')->get(['id', 'name']))
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('type_label', 'type')
                ->dataSource([
                    ['id' => 'multiple_choice', 'name' => 'Trắc nghiệm'],
                    ['id' => 'essay',           'name' => 'Tự luận'],
                    ['id' => 'hybrid',          'name' => 'Hỗn hợp'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('mode_label', 'mode')
                ->dataSource([
                    ['id' => 'practice', 'name' => 'Luyện tập'],
                    ['id' => 'official', 'name' => 'Thi chính thức'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('status_label', 'status')
                ->dataSource([
                    // ['id' => 'draft',    'name' => 'Bản nháp'],
                    ['id' => 'pending',  'name' => 'Chờ duyệt'],
                    ['id' => 'approved', 'name' => 'Đã duyệt'],
                    ['id' => 'rejected', 'name' => 'Từ chối'],
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

    public function actions(Exam $row): array
    {
        return [
            Button::add('view')
                // ->slot('Xem')
                // ->class('inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-700')
                ->slot('👁 Xem')
                ->class($this->buttonClass(
                    'from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 focus:ring-blue-500'
                ))
                ->dispatch('open-view-modal', ['examId' => $row->id]),

            Button::add('edit')
                // ->slot('Chỉnh sửa')
                // ->class('inline-flex items-center rounded-md bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600')
                ->slot('✍ Cập nhật')
                ->class($this->buttonClass(
                    'from-amber-500 to-orange-600 hover:from-amber-400 hover:to-orange-500 focus:ring-orange-500'
                ))
                ->dispatch('open-edit-modal', ['examId' => $row->id]),

            Button::add('show')
                // ->slot('Chi tiết')
                // ->class('text-blue-600 hover:text-blue-800')
                ->slot('Quản lí')
                 ->class($this->buttonClass(
                    'from-violet-500 to-indigo-600 hover:from-violet-400 hover:to-indigo-500 focus:ring-violet-500'
                ))
                ->route('contributor.exams.detail', ['examId' => $row->id]),

            Button::add('delete')
                // ->slot('Xóa')
                // ->class('inline-flex items-center rounded-md bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-700')
                ->slot('🗑 Xóa')
                ->class($this->buttonClass(
                    'from-red-500 to-rose-600 hover:from-red-400 hover:to-rose-500 focus:ring-red-500'
                ))
                ->dispatch('open-delete-confirm', ['examId' => $row->id]),
        ];
    }
    private function buttonClass(string $gradient): string
    {
         return "inline-flex items-center justify-center rounded-md
            px-2 py-1.5
            text-xs font-medium text-white
            whitespace-nowrap

            bg-gradient-to-r {$gradient}

            shadow-sm
            transition-all duration-200

            hover:-translate-y-0.5
            hover:shadow-md

            active:scale-95

            focus:outline-none
            focus:ring-2
            focus:ring-offset-2";
    }
    public function actionRules($row): array
    {
        return [
             // Chưa publish hoac chua co cau hoi
            Rule::rows()
                ->when(fn (Exam $exam) => $exam->questions_count === 0 || is_null($exam->publish_at) || $exam->status === 'rejected')
                ->setAttribute(
                    'class',
                    '!bg-red-100'
                ),
            // Chờ duyệt
            Rule::rows()
                ->when(fn (Exam $exam) => $exam->status === 'pending')
        
                ->setAttribute(
                    'class',
                    '!bg-yellow-100'
                ),
            // Đã duyệt
            Rule::rows()
                ->when(fn (Exam $exam) => $exam->status === 'approved')
                ->setAttribute(
                    'class',
                    '!bg-green-100'
                ),
            ];
    }

    /*
    |--------------------------------------------------------------------------
    | Event Listeners
    |--------------------------------------------------------------------------
    */

    #[On('open-view-modal')]
    public function openViewModal(int $examId): void
    {
        $this->dispatch('exam-view', id: $examId)
            ->to(ExamModal::class);
    }

    #[On('open-edit-modal')]
    public function openEditModal(int $examId): void
    {
        $this->dispatch('exam-edit', id: $examId)
            ->to(ExamModal::class);
    }

    #[On('open-create-modal')]
    public function openCreateModal(): void
    {
        $this->dispatch('exam-create')
            ->to(ExamModal::class);
    }

    #[On('open-delete-confirm')]
    public function openDeleteConfirm(int $examId): void
    {
        $this->dispatch('exam-delete', id: $examId)
            ->to(ExamModal::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Mapping Helpers
    |--------------------------------------------------------------------------
    */

    private function mapExamType(string $type): string
    {
        return match ($type) {
            'multiple_choice' => 'Trắc nghiệm',
            'essay'           => 'Tự luận',
            'hybrid'          => 'Hỗn hợp',
            default           => $type,
        };
    }

    private function mapExamMode(string $mode): string
    {
        return match ($mode) {
            'practice' => 'Luyện tập',
            'official' => 'Thi chính thức',
            default    => $mode,
        };
    }

    private function mapExamStatus(string $status): string
    {
        return match ($status) {
            // 'draft'    => 'Bản nháp',
            'pending'  => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            default    => $status,
        };
    }
}
