<?php

namespace Modules\Auth\Livewire\Admin;

use Livewire\Attributes\Layout;
use Modules\Auth\Models\ContributorApplication;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\Rule;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

#[Layout('layouts.admin')]
final class AdminCtvApplications extends PowerGridComponent
{
    public string $tableName = 'admin-ctv-applications-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return ContributorApplication::query()
            ->with(['user', 'reviewer'])
            ->select([
                'contributor_applications.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.phone as user_phone'
            ])
            ->leftJoin('users', 'contributor_applications.user_id', '=', 'users.id');
    }

    public function relationSearch(): array
    {
        return [
            'user' => [
                'name',
                'email',
                'phone',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()

            ->add('id')

            ->add('user_name', fn ($row) => $row->user->name)

            ->add('user_email', fn ($row) => $row->user->email)

            ->add('user_phone', fn ($row) => $row->user->phone)

            ->add('expertise')

            ->add('status')

            ->add(
                'status_label',
                function ($row): string {
                    return match ($row->status) {
                        'pending' => '
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                Chờ duyệt
                            </span>',

                        'approved' => '
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Đã duyệt
                            </span>',

                        'rejected' => '
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                                Từ chối
                            </span>',

                        default => '
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">
                                Không xác định
                            </span>',
                    };
                }
            )

            ->add(
                'created_at_formatted',
                fn ($row) => $row->created_at->format('d/m/Y H:i')
            )

            ->add(
                'reviewed_at_formatted',
                fn ($row) => $row->reviewed_at
                    ? $row->reviewed_at->format('d/m/Y H:i')
                    : '<span class="text-gray-400 text-sm">Chưa duyệt</span>'
            );
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Tên người dùng', 'user_name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'user_email')
                ->sortable()
                ->searchable(),

            Column::make('Số điện thoại', 'user_phone')
                ->sortable()
                ->searchable(),

            Column::make('Chuyên môn', 'expertise')
                ->sortable()
                ->searchable(),

            Column::make('Trạng thái', 'status_label')
                ->sortable('status'),

            Column::make('Ngày đăng ký', 'created_at_formatted')
                ->sortable('created_at'),

            Column::make('Ngày duyệt', 'reviewed_at_formatted')
                ->sortable('reviewed_at'),

            Column::action('Thao tác'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::select('status', 'status')
                ->dataSource([
                    ['label' => 'Tất cả', 'value' => ''],
                    ['label' => 'Chờ duyệt', 'value' => 'pending'],
                    ['label' => 'Đã duyệt', 'value' => 'approved'],
                    ['label' => 'Từ chối', 'value' => 'rejected'],
                ])
                ->optionLabel('label')
                ->optionValue('value'),
        ];
    }

    /**
     * Actions - Chỉ giữ nút "Xem chi tiết"
     */
    public function actions(ContributorApplication $row): array
    {
        return [
            Button::add('view')
                ->slot('<svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Xem chi tiết')
                ->id()
                ->class('inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors')
                ->route('admin.ctv.detail', ['id' => $row->id]),
        ];
    }

    /**
     * Action Rules - Styling cho từng row theo status
     */
    public function actionRules($row): array
    {
        return [
            // Style row theo trạng thái
            Rule::rows()
                ->when(fn($row) => $row->status === 'pending')
                ->setAttribute('class', 'bg-yellow-50 hover:bg-yellow-100 transition-colors'),

            Rule::rows()
                ->when(fn($row) => $row->status === 'approved')
                ->setAttribute('class', 'bg-green-50 hover:bg-green-100 transition-colors'),

            Rule::rows()
                ->when(fn($row) => $row->status === 'rejected')
                ->setAttribute('class', 'bg-red-50 hover:bg-red-100 transition-colors'),
        ];
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        ContributorApplication::query()->find($id)->update([
            $field => e($value),
        ]);
    }
}
