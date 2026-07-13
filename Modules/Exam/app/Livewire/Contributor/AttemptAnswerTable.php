<?php

namespace Modules\Exam\Livewire\Contributor;

use Illuminate\Database\Eloquent\Builder;
use Modules\Exam\Models\AttemptAnswer;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use WireUi\Traits\WireUiActions;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use Livewire\Attributes\On;

final class AttemptAnswerTable extends PowerGridComponent
{
    use WireUiActions;
    
    public string $tableName = 'attempt-answer-table';
    public int $attemptId;
    public string $gradingStatus = 'all';
    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
            ->showToggleColumns()
            ->showSearchInput(),
            PowerGrid::footer()
            ->showPerPage(
                perPage: 10,
                perPageValues: [10, 25, 50, 100]
            ),
            // ->showRecordCount(),
            // PowerGrid::detail(),
            // PowerGrid::responsive(),
        ];
    }

    public function header(): array
    {
        return [
            Button::add('all')
                ->slot('Tất cả ('.$this->totalCount().')')
                ->class(
                    $this->gradingStatus === 'all'
                        ? 'bg-blue-500 text-white px-3 py-2 rounded'
                        : 'bg-gray-100 px-3 py-2 rounded'
                )
                ->dispatch('grading-status', [
                    'status' => 'all',
                ]),

            Button::add('pending')
                ->slot('Chưa chấm ('.$this->pendingCount().')')
                ->class(
                    $this->gradingStatus === 'pending'
                        ? 'bg-red-500 text-white px-3 py-2 rounded'
                        : 'bg-gray-100 px-3 py-2 rounded'
                )
                ->dispatch('grading-status', [
                    'status' => 'pending',
                ]),

            Button::add('graded')
                ->slot('Đã chấm ('.$this->gradedCount().')')
                ->class(
                    $this->gradingStatus === 'graded'
                        ? 'bg-green-500 text-white px-3 py-2 rounded'
                        : 'bg-gray-100 px-3 py-2 rounded'
                )
                ->dispatch('grading-status', ['status' => 'graded']),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Datasource
    |--------------------------------------------------------------------------
    */

    public function datasource(): Builder
    {
        return AttemptAnswer::query()
            ->where('attempt_id', $this->attemptId)
            ->when(
                $this->gradingStatus === 'pending',
                fn ($q) => $q->where('status', 'pending')
            )
            ->when(
                $this->gradingStatus === 'graded',
                fn ($q) => $q->whereIn('status', [
                    'correct',
                    'incorrect',
                ])
            )
            ->with([
                'question',
                'question.options:id,question_id,option_key',
            ]);
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

            ->add('question_content', fn (AttemptAnswer $model) =>
                // $model->question?->content
                str(strip_tags($model->question?->content))
                ->words(4, '...')
            )

            ->add('question_type', fn (AttemptAnswer $model) =>
                $this->mapType($model->question?->type)
            )

            // ->add('selected_options', fn (AttemptAnswer $model) =>
            //     is_array($model->selected_option_ids)
            //         ? implode(',', $model->selected_option_ids)
            //         : '—'
            // )
            ->add('selected_options', function (AttemptAnswer $model) {
                if (
                    empty($model->selected_option_ids)
                    || $model->question === null
                ) {
                    return '—';
                }

                return $model->question
                    ->options
                    ->whereIn('id', $model->selected_option_ids)
                    ->sortBy('option_key')
                    ->pluck('option_key')
                    ->implode(', ');
            })

            ->add('answer_text')

            // ->add('is_correct_label', fn (AttemptAnswer $model) =>
            //     match ($model->is_correct) {
            //         true => 'Correct',
            //         false => 'Wrong',
            //         default => 'Pending',
            //     }
            // )

            ->add('question_answer_text', function (AttemptAnswer $model) {

                if ($model->question?->type !== 'essay') {
                    return '—';
                }

                return '
                    <span class="inline-flex rounded-md bg-green-100 px-2 py-1 text-xs font-medium text-green-700">
                        '.e(str($model->question?->answer_text)->limit(100)).'
                    </span>
                ';
            })
            ->add('status')
            ->add('status_actions', function (AttemptAnswer $model) {
                $correctClass = $model->status === 'correct'
                    ? 'bg-green-600 text-white border-green-600'
                    : 'bg-white text-gray-700 border-gray-300';

                $incorrectClass = $model->status === 'incorrect'
                    ? 'bg-red-600 text-white border-red-600'
                    : 'bg-white text-gray-700 border-gray-300';

                if ($model->question->type === 'essay') {

                    return '
                        <button
                            wire:click="$dispatch(\'attempt-answer-grade\', {
                                attemptAnswerId: '.$model->id.',
                                status: \'correct\'
                            })"
                            class="rounded-md border px-3 py-1 text-xs font-medium '.$correctClass.'"
                        >
                            ✓ Correct
                        </button>

                        <button
                            wire:click="$dispatch(\'attempt-answer-grade\', {
                                attemptAnswerId: '.$model->id.',
                                status: \'incorrect\'
                            })"
                            class="rounded-md border px-3 py-1 text-xs font-medium '.$incorrectClass.'"
                        >
                            ✗ Incorrect
                        </button>
                    ';
                }

                return '
                    <div class="flex items-center gap-2">
                        <button
                            wire:click="markCorrect('.$model->id.')"
                            class="rounded-md border px-3 py-1 text-xs font-medium '.$correctClass.'"
                        >
                            ✓ Correct
                        </button>

                        <button
                            wire:click="markIncorrect('.$model->id.')"
                            class="rounded-md border px-3 py-1 text-xs font-medium '.$incorrectClass.'"
                        >
                            ✗ Incorrect
                        </button>
                    </div>
                ';
            })

            ->add('answered_at_formatted', fn (AttemptAnswer $model) =>
                $model->answered_at?->format('d/m/Y H:i')
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Columns
    |--------------------------------------------------------------------------
    */

    public function columns(): array
    {
        return [
            Column::make('ID', 'id'),
                // ->sortable(),

            Column::make('Câu hỏi', 'question_content')
                ->searchable(),

            Column::make('Loại câu hỏi', 'question_type')
                ->sortable(),

            Column::make('lựa chọn', 'selected_options'),

            Column::make('Tự luận', 'answer_text'),
            Column::make('Đáp án mẫu', 'question_answer_text'),

            // Column::make('Result', 'is_correct_label', 'is_correct')
            //     ->sortable(),

            // Column::make('Status', 'status_toggle', 'status')
            //     ->sortable(),
            Column::make('Trạng thái', 'status_actions', 'status')
                ->sortable(),

            Column::make('Thời gian làm', 'answered_at_formatted', 'answered_at')
                ->sortable(),

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
            Filter::select('status', 'status')
                ->dataSource([
                    // ['id' => 1, 'name' => 'Correct'],
                    // ['id' => 0, 'name' => 'Wrong'],
                    ['id' => 'pending', 'name' => 'Chưa chấm'],
                    ['id' => 'correct', 'name' => 'Đúng'],
                    ['id' => 'incorrect', 'name' => 'Sai'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),

            Filter::select('question_type', 'question_type')
                ->dataSource([
                    ['id' => 'single_choice', 'name' => 'Một đáp án'],
                    ['id' => 'multiple_choice', 'name' => 'Nhiều đáp án'],
                    ['id' => 'essay', 'name' => 'Tự luận'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),
        ];
    }
    public function actionRules($row): array
    {
        return [
            Rule::rows()
                ->when(fn ($answer) => $answer->status === 'pending')
                ->setAttribute('class', 'bg-red-50 border-l-4 border-red-500'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    public function actions(AttemptAnswer $row): array
    {
        return [
            Button::add('view')
                ->slot('Xem')
                ->class(
                    'inline-flex items-center rounded-md bg-blue-600 px-3 py-1.5 text-xs text-white hover:bg-blue-700'
                )
                ->dispatch('open-answer', [
                    'answerId' => $row->id,
                ]),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function mapType(?string $type): string
    {
        return match ($type) {
            'single_choice' => 'Một đáp án',
            'multiple_choice' => 'Nhiều đáp án',
            'essay' => 'Tự luận',
            default => $type ?? '—',
        };
    }

    public function markCorrect(int $answerId): void
    {
        $answer = AttemptAnswer::findOrFail($answerId);

         $question = $answer->attempt
            ->exam
            ->questions
            ->firstWhere('id', $answer->question_id);

        $answer->update([
            'status' => 'correct',
            'score'  => $question?->pivot?->score ?? 0,
            'is_correct'=>1,
        ]);

        $this->notification()->success(
            title: 'Chấm điểm thành công!',
            description: "Câu {$question?->pivot?->sort_order} được đánh dấu là correct."
        );

        $this->refresh();
    }

    public function markIncorrect(int $answerId): void
    {
        $answer = AttemptAnswer::findOrFail($answerId);

        $question = $answer->attempt
            ->exam
            ->questions
            ->firstWhere('id', $answer->question_id);

        $answer->update([
            'status' => 'incorrect',
            'score' => 0,
            'is_correct'=>0,
        ]);

        $this->notification()->warning(
            title: 'Đã cập nhật!',
            description: "Câu {$question?->pivot?->sort_order} được đánh dấu là Incorrect."
        );

        $this->refresh();
    }

    #[On('grading-status')]
    public function changeGradingStatus(string $status): void
    {
        $this->gradingStatus =$status;

        $this->resetPage();
    }

    public function totalCount(): int
    {
        return AttemptAnswer::query()
            ->where('attempt_id', $this->attemptId)
            ->count();
    }

    public function pendingCount(): int
    {
        return AttemptAnswer::query()
            ->where('attempt_id', $this->attemptId)
            ->where('status', 'pending')
            ->count();
    }

    public function gradedCount(): int
    {
        return AttemptAnswer::query()
            ->where('attempt_id', $this->attemptId)
            ->whereIn('status', [
                'correct',
                'incorrect',
            ])
            ->count();
    }
}