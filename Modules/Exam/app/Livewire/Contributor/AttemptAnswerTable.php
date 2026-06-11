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

final class AttemptAnswerTable extends PowerGridComponent
{
    public string $tableName = 'attempt-answer-table';

    public int $attemptId;

    /*
    |--------------------------------------------------------------------------
    | Datasource
    |--------------------------------------------------------------------------
    */

    public function datasource(): Builder
    {
        return AttemptAnswer::query()
            ->where('attempt_id', $this->attemptId)
            ->with([
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
                $model->question?->content
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

            ->add('is_correct_label', fn (AttemptAnswer $model) =>
                match ($model->is_correct) {
                    true => 'Correct',
                    false => 'Wrong',
                    default => 'Pending',
                }
            )

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
            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Question', 'question_content')
                ->searchable(),

            Column::make('Type', 'question_type'),

            Column::make('Selected', 'selected_options'),

            Column::make('Answer', 'answer_text'),

            Column::make('Result', 'is_correct_label', 'is_correct')
                ->sortable(),

            Column::make('Answered At', 'answered_at_formatted', 'answered_at')
                ->sortable(),

            Column::action('Actions'),
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
            Filter::select('is_correct', 'is_correct')
                ->dataSource([
                    ['id' => 1, 'name' => 'Correct'],
                    ['id' => 0, 'name' => 'Wrong'],
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

    public function actions(AttemptAnswer $row): array
    {
        return [
            Button::add('view')
                ->slot('View')
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
            'single_choice' => 'Single',
            'multiple_choice' => 'Multiple',
            'essay' => 'Essay',
            default => $type ?? '—',
        };
    }
}