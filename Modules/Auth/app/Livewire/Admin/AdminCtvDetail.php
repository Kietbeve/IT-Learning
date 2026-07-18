<?php

namespace Modules\Auth\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Auth\Models\ContributorApplication;
use Modules\Auth\Services\AuthService;
use App\Models\User;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.admin')]
class AdminCtvDetail extends Component
{
    use WireUiActions;

    public ContributorApplication $application;
    public User $user;
    
    // Modal states
    public bool $showApproveModal = false;
    public bool $showRejectModal = false;
    
    // Form data
    public string $rejectReason = '';
    
    // Service
    protected AuthService $authService;

    /**
     * Inject AuthService vào component
     */
    public function boot(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Khởi tạo component với ID đơn đăng ký
     */
    public function mount($id)
    {
        $this->application = ContributorApplication::with(['user', 'reviewer'])->findOrFail($id);
        $this->user = $this->application->user;
    }

    public function render()
    {
        return view('auth::livewire.admin.admin-ctv-detail');
    }

    // === MODAL HANDLERS ===
    
    /**
     * Hiển thị modal xác nhận duyệt đơn
     */
    public function openApproveModal()
    {
        if (!$this->canProcess()) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Chỉ có thể xử lý các đơn đang chờ duyệt.'
            );
            return;
        }
        
        $this->showApproveModal = true;
    }

    /**
     * Đóng modal duyệt đơn
     */
    public function closeApproveModal()
    {
        $this->showApproveModal = false;
    }

    /**
     * Hiển thị modal từ chối đơn
     */
    public function openRejectModal()
    {
        if (!$this->canProcess()) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Chỉ có thể xử lý các đơn đang chờ duyệt.'
            );
            return;
        }
        
        $this->showRejectModal = true;
        $this->rejectReason = '';
    }

    /**
     * Đóng modal từ chối đơn
     */
    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->rejectReason = '';
        $this->resetValidation('rejectReason');
    }

    // === PROCESSING ACTIONS ===
    
    /**
     * Xác nhận duyệt đơn
     */
    public function confirmApprove()
    {
        $this->processApplication('approve');
    }

    /**
     * Xác nhận từ chối đơn
     */
    public function confirmReject()
    {
        // Validate lý do từ chối
        $this->validate([
            'rejectReason' => 'required|string|min:10|max:500',
        ], [
            'rejectReason.required' => 'Vui lòng nhập lý do từ chối.',
            'rejectReason.min' => 'Lý do từ chối phải có ít nhất 10 ký tự.',
            'rejectReason.max' => 'Lý do từ chối không được vượt quá 500 ký tự.',
        ]);

        $this->processApplication('reject', $this->rejectReason);
    }

    /**
     * Xử lý duyệt/từ chối đơn thông qua AuthService
     * 
     * @param string $action - 'approve' hoặc 'reject'
     * @param string|null $reason - Lý do từ chối (nếu action = 'reject')
     */
    private function processApplication(string $action, ?string $reason = null)
    {
        try {
            // Gọi service xử lý
            $result = $this->authService->processContributorApplication(
                $this->application->id,
                $action,
                auth()->id(),
                $reason
            );

            if ($result['success']) {
                // Cập nhật lại application từ kết quả
                $this->application = $result['application'];
                
                // Đóng modal
                $this->closeApproveModal();
                $this->closeRejectModal();
                
                // Hiển thị thông báo thành công
                $this->notification()->success(
                    title: 'Thành công!',
                    description: $result['message']
                );
            } else {
                // Hiển thị lỗi
                $this->notification()->error(
                    title: 'Lỗi!',
                    description: $result['message']
                );
            }
        } catch (\Exception $e) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Có lỗi xảy ra: ' . $e->getMessage()
            );
        }
    }

    // === NAVIGATION ===
    
    /**
     * Quay lại danh sách đơn CTV
     */
    public function backToList()
    {
        return $this->redirect(route('admin.ctv.list'));
    }

    // === HELPER METHODS ===
    
    /**
     * Kiểm tra có thể xử lý đơn không
     */
    public function canProcess(): bool
    {
        return $this->application->status === 'pending' && auth()->check();
    }

    /**
     * Lấy badge HTML cho trạng thái
     */
    public function getStatusBadge(): string
    {
        return match ($this->application->status) {
            'pending' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
                            Chờ duyệt
                        </span>',
            'approved' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Đã duyệt
                        </span>',
            'rejected' => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            Từ chối
                        </span>',
            default => '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800">Không xác định</span>',
        };
    }

    /**
     * Lấy màu trạng thái cho WireUI
     */
    public function getStatusColor(): string
    {
        return match ($this->application->status) {
            'pending' => 'warning',
            'approved' => 'success', 
            'rejected' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Format ngày tạo đơn
     */
    public function formatCreatedAt(): string
    {
        return $this->application->created_at->format('d/m/Y H:i:s');
    }

    /**
     * Format ngày duyệt
     */
    public function formatReviewedAt(): string
    {
        return $this->application->reviewed_at 
            ? $this->application->reviewed_at->format('d/m/Y H:i:s')
            : 'Chưa duyệt';
    }

    /**
     * Lấy tên người duyệt
     */
    public function getReviewerName(): string
    {
        return $this->application->reviewer 
            ? $this->application->reviewer->name
            : 'Chưa có';
    }

    /**
     * Kiểm tra có CV không
     */
    public function hasCV(): bool
    {
        return !empty($this->application->cv_url);
    }

    /**
     * Lấy URL CV
     */
    public function getCVUrl(): ?string
    {
        return $this->hasCV() ? asset('storage/' . $this->application->cv_url) : null;
    }

    /**
     * Kiểm tra user có role contributor chưa
     */
    public function isContributor(): bool
    {
        return $this->user->hasRole('contributor');
    }
}
