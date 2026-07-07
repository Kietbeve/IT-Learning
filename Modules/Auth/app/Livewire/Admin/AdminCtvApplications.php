<?php

namespace Modules\Auth\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Auth\Models\ContributorApplication;
use App\Models\User;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Exportable;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Footer;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridColumns;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\WithExport;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Facades\Rule;

#[Layout('layouts.admin')]
final class AdminCtvApplications extends PowerGridComponent
{
    use WithExport;

    public string $tableName = 'admin-ctv-applications-table';

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()
                ->showSearchInput()
                ->showToggleColumns(),
            Footer::make()
                ->showPerPage(15, [15, 25, 50, 100])
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

    public function addColumns(): PowerGridColumns
    {
        return PowerGrid::columns()
            ->addColumn('id')
            ->addColumn('user_name')
            ->addColumn('user_email') 
            ->addColumn('user_phone')
            ->addColumn('expertise')
            ->addColumn('status')
            ->addColumn('status_label', fn($row) => $this->getStatusLabel($row->status))
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn($row) => $row->created_at->format('d/m/Y H:i'))
            ->addColumn('reviewed_at')
            ->addColumn('reviewed_at_formatted', fn($row) => $row->reviewed_at ? $row->reviewed_at->format('d/m/Y H:i') : 'Chưa duyệt');
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->sortable()
                ->searchable(),

            Column::make('Tên người dùng', 'user_name', 'users.name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'user_email', 'users.email')
                ->sortable()
                ->searchable(),

            Column::make('Số điện thoại', 'user_phone', 'users.phone')
                ->sortable()
                ->searchable(),

            Column::make('Chuyên môn', 'expertise')
                ->sortable()
                ->searchable(),

            Column::make('Trạng thái', 'status_label')
                ->sortable(),

            Column::make('Ngày đăng ký', 'created_at_formatted')
                ->sortable(),

            Column::make('Ngày duyệt', 'reviewed_at_formatted')
                ->sortable(),

            Column::action('Thao tác')
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

    public function actions(ContributorApplication $row): array
    {
        return [
            Button::add('view')
                ->slot('Xem chi tiết')
                ->id()
                ->class('btn btn-sm btn-info')
                ->dispatch('view-application', ['applicationId' => $row->id]),

            Button::add('approve')
                ->slot('Duyệt')
                ->id()
                ->class('btn btn-sm btn-success')
                ->when(fn($row) => $row->status === 'pending')
                ->dispatch('approve-application', ['applicationId' => $row->id]),

            Button::add('reject')
                ->slot('Từ chối')
                ->id()
                ->class('btn btn-sm btn-danger')
                ->when(fn($row) => $row->status === 'pending')
                ->dispatch('reject-application', ['applicationId' => $row->id]),
        ];
    }

    public function actionRules($row): array
    {
        return [
            Rule::button('approve')
                ->when(fn($row) => $row->status !== 'pending')
                ->hide(),
            
            Rule::button('reject')
                ->when(fn($row) => $row->status !== 'pending')
                ->hide(),
        ];
    }

    protected function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending' => '<span class="badge badge-warning">Chờ duyệt</span>',
            'approved' => '<span class="badge badge-success">Đã duyệt</span>',
            'rejected' => '<span class="badge badge-danger">Từ chối</span>',
            default => '<span class="badge badge-secondary">Không xác định</span>',
        };
    }

    public function onUpdatedToggleable(string|int $id, string $field, string $value): void
    {
        ContributorApplication::query()->find($id)->update([
            $field => e($value),
        ]);
    }

    // Event listeners
    protected function getListeners()
    {
        return [
            'view-application' => 'viewApplication',
            'approve-application' => 'approveApplication', 
            'reject-application' => 'rejectApplication',
        ];
    }

    public function viewApplication($applicationId)
    {
        $this->redirect(route('admin.ctv.detail', ['id' => $applicationId]));
    }

    public function approveApplication($applicationId)
    {
        try {
            $application = ContributorApplication::findOrFail($applicationId);
            
            $application->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'rejected_reason' => null
            ]);

            // Update user to be contributor
            $application->user->update([
                'is_contributor' => true
            ]);

            $this->dispatch('swal:success', [
                'title' => 'Thành công!',
                'text' => 'Đã duyệt đơn đăng ký CTV thành công.',
            ]);

            $this->fillData();

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Lỗi!',
                'text' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ]);
        }
    }

    public function rejectApplication($applicationId)
    {
        $this->dispatch('show-reject-modal', ['applicationId' => $applicationId]);
    }

    public function confirmReject($applicationId, $reason = '')
    {
        try {
            $application = ContributorApplication::findOrFail($applicationId);
            
            $application->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'rejected_reason' => $reason
            ]);

            $this->dispatch('swal:success', [
                'title' => 'Thành công!',
                'text' => 'Đã từ chối đơn đăng ký CTV.',
            ]);

            $this->fillData();

        } catch (\Exception $e) {
            $this->dispatch('swal:error', [
                'title' => 'Lỗi!',
                'text' => 'Có lỗi xảy ra: ' . $e->getMessage(),
            ]);
        }
    }
}