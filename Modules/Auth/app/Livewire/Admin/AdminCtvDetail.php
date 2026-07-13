<?php

namespace Modules\Auth\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Auth\Models\ContributorApplication;
use App\Models\User;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.admin')]
class AdminCtvDetail extends Component
{
    use WireUiActions;

    public ContributorApplication $application;
    public User $user;
    public string $rejectReason = '';
    public bool $showRejectModal = false;

    public function mount($id)
    {
        $this->application = ContributorApplication::with(['user', 'reviewer'])->findOrFail($id);
        $this->user = $this->application->user;
    }

    public function render()
    {
        return view('livewire.admin.admin-ctv-detail');
    }

    public function approveApplication()
    {
        if ($this->application->status !== 'pending') {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Chỉ có thể duyệt các đơn đang chờ xét duyệt.'
            );
            return;
        }

        try {
            $this->application->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'rejected_reason' => null
            ]);

            // Update user to be contributor
            $this->user->update([
                'is_contributor' => true
            ]);

            $this->notification()->success(
                title: 'Thành công!',
                description: 'Đã duyệt đơn đăng ký CTV thành công.'
            );

            // Log activity
            activity()
                ->performedOn($this->application)
                ->causedBy(auth()->user())
                ->withProperties([
                    'user_id' => $this->user->id,
                    'user_name' => $this->user->name,
                    'action' => 'approved'
                ])
                ->log('Duyệt đơn đăng ký CTV');

        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Có lỗi xảy ra: ' . $e->getMessage()
            );
        }
    }

    public function showRejectModal()
    {
        if ($this->application->status !== 'pending') {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Chỉ có thể từ chối các đơn đang chờ xét duyệt.'
            );
            return;
        }

        $this->showRejectModal = true;
        $this->rejectReason = '';
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectReason = '';
    }

    public function rejectApplication()
    {
        $this->validate([
            'rejectReason' => 'required|string|min:10|max:500',
        ], [
            'rejectReason.required' => 'Vui lòng nhập lý do từ chối.',
            'rejectReason.min' => 'Lý do từ chối phải có ít nhất 10 ký tự.',
            'rejectReason.max' => 'Lý do từ chối không được vượt quá 500 ký tự.',
        ]);

        try {
            $this->application->update([
                'status' => 'rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'rejected_reason' => $this->rejectReason
            ]);

            $this->notification()->success(
                title: 'Thành công!',
                description: 'Đã từ chối đơn đăng ký CTV.'
            );

            // Log activity
            activity()
                ->performedOn($this->application)
                ->causedBy(auth()->user())
                ->withProperties([
                    'user_id' => $this->user->id,
                    'user_name' => $this->user->name,
                    'action' => 'rejected',
                    'reason' => $this->rejectReason
                ])
                ->log('Từ chối đơn đăng ký CTV');

            $this->closeRejectModal();

        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Có lỗi xảy ra: ' . $e->getMessage()
            );
        }
    }

    public function backToList()
    {
        return $this->redirect(route('admin.ctv.list'));
    }

    public function getStatusBadge()
    {
        return match ($this->application->status) {
            'pending' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Chờ duyệt</span>',
            'approved' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Đã duyệt</span>',
            'rejected' => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Từ chối</span>',
            default => '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Không xác định</span>',
        };
    }

    public function getStatusColor()
    {
        return match ($this->application->status) {
            'pending' => 'warning',
            'approved' => 'success', 
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    public function formatCreatedAt()
    {
        return $this->application->created_at->format('d/m/Y H:i:s');
    }

    public function formatReviewedAt()
    {
        return $this->application->reviewed_at 
            ? $this->application->reviewed_at->format('d/m/Y H:i:s')
            : 'Chưa duyệt';
    }

    public function getReviewerName()
    {
        return $this->application->reviewer 
            ? $this->application->reviewer->name
            : 'Chưa có';
    }

    public function canApprove()
    {
        return $this->application->status === 'pending' && auth()->check();
    }

    public function canReject()
    {
        return $this->application->status === 'pending' && auth()->check();
    }

    public function hasCV()
    {
        return !empty($this->application->cv_url);
    }

    public function getCVUrl()
    {
        return $this->hasCV() ? asset('storage/' . $this->application->cv_url) : null;
    }
}