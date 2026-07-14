<?php

namespace Modules\Exam\Livewire\Contributor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\Exam\Models\ExamAttempt;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

class ExamAttemptTable extends PowerGridComponent
{
    public string $tableName = 'exam-attempt-table';
    public string $primaryKey = 'id';
    public int $examId;//chỉ lấy những bài làm thuộc đề thi
    public string $sortField = 'id';

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput(),

            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return ExamAttempt::query()
            ->where('exam_id', $this->examId)//chỉ lấy những bài làm thuộc đề thi
            ->with([
                'exam:id,title',
                'user:id,name',
            ]);
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()

            ->add('id')

            ->add('exam_title', fn (ExamAttempt $attempt) => $attempt->exam?->title)

            ->add('user_name', fn (ExamAttempt $attempt) => $attempt->user?->name)

            ->add('status')

            ->add('score')

            ->add(
                'percent_score_formatted',
                fn (ExamAttempt $attempt) => $attempt->percent_score !== null
                    ? number_format($attempt->percent_score, 2) . '%'
                    : '-'
            )

            ->add(
                'is_passed_badge',
                function (ExamAttempt $attempt): string {
                    return match ($attempt->is_passed) {
                        true => '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Passed</span>',
                        false => '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Failed</span>',
                        default => '<span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-700">Pending</span>',
                    };
                }
            )

            ->add(
                'status_badge',
                function (ExamAttempt $attempt): string {
                    return match ($attempt->status) {
                        'in_progress' => '<span class="px-2 py-1 text-xs rounded bg-blue-100 text-blue-700">In Progress</span>',
                        'submitted' => '<span class="px-2 py-1 text-xs rounded bg-green-100 text-green-700">Submitted</span>',
                        'auto_submitted' => '<span class="px-2 py-1 text-xs rounded bg-amber-100 text-amber-700">Auto Submitted</span>',
                        'completed' => '<span class="px-2 py-1 text-xs rounded bg-emerald-100 text-emerald-700">Completed</span>',
                        'canceled' => '<span class="px-2 py-1 text-xs rounded bg-red-100 text-red-700">Canceled</span>',
                        default => $attempt->status,
                    };
                }
            )

            ->add(
                'started_at_formatted',
                fn (ExamAttempt $attempt) => $attempt->started_at?->format('d/m/Y H:i')
            )

            ->add(
                'submitted_at_formatted',
                fn (ExamAttempt $attempt) => $attempt->submitted_at?->format('d/m/Y H:i')
            );

            // ->add(
            //     'created_at_formatted',
            //     fn (ExamAttempt $attempt) => Carbon::parse($attempt->created_at)
            //         ->format('d/m/Y H:i')
            // );
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            // Column::make('Exam', 'exam_title'),

            Column::make('User', 'user_name')
                ->searchable(),

            Column::make('Status', 'status_badge')
                ->sortable('status'),
                // ->html(),

            Column::make('Score', 'score')
                ->sortable(),

            Column::make('Percent', 'percent_score_formatted')
                ->sortable('percent_score'),

            Column::make('Passed', 'is_passed_badge')
                ->sortable('is_passed'),
                // ->html(),

            Column::make('Started At', 'started_at_formatted')
                ->sortable('started_at'),

            Column::make('Submitted At', 'submitted_at_formatted')
                ->sortable('submitted_at'),

            // Column::make('Created At', 'created_at_formatted')
            //     ->sortable('created_at'),

            Column::action('Actions'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('status', 'status')
                ->dataSource([
                    ['id' => 'in_progress', 'name' => 'In Progress'],
                    ['id' => 'submitted', 'name' => 'Submitted'],
                    ['id' => 'auto_submitted', 'name' => 'Auto Submitted'],
                    ['id' => 'completed', 'name' => 'Completed'],
                    ['id' => 'canceled', 'name' => 'Canceled'],
                ])
                ->optionValue('id')
                ->optionLabel('name'),

            Filter::boolean('is_passed'),
        ];
    }

    public function actions($row): array
    {
        return [
            Button::add('view')
                ->slot('View')
                ->class(
                    'inline-flex items-center px-3 py-1 text-sm rounded bg-primary-600 text-white'
                )
                ->route(
                    'contributor.exams.attempts.answer',
                    [
                        'attemptId' => $row->id,
                    ]
                ),
        ];
    }
}