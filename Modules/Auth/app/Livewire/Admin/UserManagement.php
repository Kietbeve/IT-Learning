<?php

namespace Modules\Auth\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Modules\Auth\Services\AuthService;
use App\Models\User;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.admin')]
class UserManagement extends Component
{
    use WithPagination;
    use WireUiActions;

    // Properties cho search và filter
    public $search = '';
    public $roleFilter = '';
    public $statusFilter = '';
    
    // Properties cho pagination và sorting
    public $perPage = 15;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    
    // Properties cho modals
    public $showLockModal = false;
    public $showUnlockModal = false;
    public $showDetailModal = false;
    
    // Properties cho user được chọn
    public $selectedUserId = null;
    public $selectedUser = null;
    public $blockReason = '';

    // Properties cho User modal (Add/Edit)
    public $showUserModal = false;
    public $modalMode = 'create'; // 'create' or 'edit'
    public $editingUserId = null;
    
    // Form data
    public $form = [
        'name' => '',
        'email' => '',
        'password' => '',
        'phone' => '',
        'bio' => '',
        'roles' => '',
    ];

    /**
     * Render component với data
     */

    // public function test()
    // {
    //     dd('clicked');
    // }

    /**
     * Computed property - Lấy danh sách users với filter
     */
    public function getUsersProperty()
    {
        return app(AuthService::class)->getUsersForManagement([
            'search' => $this->search,
            'role' => $this->roleFilter,
            'status' => $this->statusFilter,
            'sortField' => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'perPage' => $this->perPage,
        ]);
    }

    /**
     * Computed property - Lấy thống kê
     */
    public function getStatisticsProperty()
    {
        return app(AuthService::class)->getUserStatistics();
    }

    /**
     * Computed property - Lấy danh sách roles từ database
     */
    public function getRolesProperty()
    {
        return \Spatie\Permission\Models\Role::all();
    }

    /**
     * Mở modal khóa user
     */
    public function openLockModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->blockReason = '';
        $this->showLockModal = true;
    }

    /**
     * Đóng modal khóa
     */
    public function closeLockModal()
    {
        $this->showLockModal = false;
        $this->selectedUserId = null;
        $this->blockReason = '';
    }

    /**
     * Xác nhận khóa user
     */
    public function confirmLock()
    {
        // Validate lý do khóa
        if (empty(trim($this->blockReason))) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Vui lòng nhập lý do khóa tài khoản'
            );
            return;
        }

        // Gọi service để khóa user
        $result = app(AuthService::class)->blockUser(
            $this->selectedUserId,
            $this->blockReason,
            auth()->id()
        );

        if ($result['success']) {
            $this->notification()->success(
                title: 'Thành công!',
                description: $result['message']
            );
            $this->closeLockModal();
            $this->resetPage(); // Reset về trang 1
        } else {
            $this->notification()->error(
                title: 'Lỗi!',
                description: $result['message']
            );
        }
    }

    /**
     * Mở modal mở khóa user
     */
    public function openUnlockModal($userId)
    {
        $this->selectedUserId = $userId;
        $this->showUnlockModal = true;
    }

    /**
     * Đóng modal mở khóa
     */
    public function closeUnlockModal()
    {
        $this->showUnlockModal = false;
        $this->selectedUserId = null;
    }

    /**
     * Xác nhận mở khóa user
     */
    public function confirmUnlock()
    {
        $result = app(AuthService::class)->unblockUser($this->selectedUserId);

        if ($result['success']) {
            $this->notification()->success(
                title: 'Thành công!',
                description: $result['message']
            );
            $this->closeUnlockModal();
            $this->resetPage();
        } else {
            $this->notification()->error(
                title: 'Lỗi!',
                description: $result['message']
            );
        }
    }

    /**
     * Xem chi tiết user
     */
    public function viewDetail($userId)
    {
        $this->selectedUser = app(AuthService::class)->getUserDetail($userId);
        $this->showDetailModal = true;
    }

    /**
     * Đóng modal chi tiết
     */
    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedUser = null;
    }

    /**
     * Reset pagination khi search thay đổi
     */
    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination khi role filter thay đổi
     */
    public function updatingRoleFilter()
    {
        $this->resetPage();
    }

    /**
     * Reset pagination khi status filter thay đổi
     */
    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    /**
     * Sắp xếp theo cột
     */
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            // Toggle direction nếu cùng field
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Đặt field mới và mặc định desc
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    /**
     * Mở modal tạo user mới
     */
    public function openCreateModal()
    {
        $this->modalMode = 'create';
        $this->editingUserId = null;
        $this->form = [
            'name' => '',
            'email' => '',
            'password' => '',
            'phone' => '',
            'bio' => '',
            'roles' => '',
        ];
        $this->showUserModal = true;
    }

    /**
     * Mở modal edit user
     */
    public function openEditModal($userId)
    {
        $user = app(AuthService::class)->getUserDetail($userId);
        
        if (!$user) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Không tìm thấy người dùng'
            );
            return;
        }

        $this->modalMode = 'edit';
        $this->editingUserId = $userId;
        $this->form = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => '', // Không hiển thị password cũ
            'phone' => $user->phone ?? '',
            'bio' => $user->bio ?? '',
            'roles' => $user->roles->first()->name ?? '',
        ];
        $this->showUserModal = true;
    }

    /**
     * Đóng modal user
     */
    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->modalMode = 'create';
        $this->editingUserId = null;
        $this->form = [
            'name' => '',
            'email' => '',
            'password' => '',
            'phone' => '',
            'bio' => '',
            'roles' => '',
        ];
    }

    /**
     * Lưu user (create hoặc update)
     */
    public function saveUser()
    {
        // Validation cơ bản
        if (empty(trim($this->form['name']))) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Vui lòng nhập tên người dùng'
            );
            return;
        }

        if (empty(trim($this->form['email']))) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Vui lòng nhập email'
            );
            return;
        }

        if (empty($this->form['roles'])) {
            $this->notification()->error(
                title: 'Lỗi!',
                description: 'Vui lòng chọn ít nhất một vai trò'
            );
            return;
        }

        // Gọi service tương ứng
        if ($this->modalMode === 'create') {
            $result = app(AuthService::class)->createUser($this->form);
        } else {
            $result = app(AuthService::class)->updateUser($this->editingUserId, $this->form);
        }

        if ($result['success']) {
            $this->notification()->success(
                title: 'Thành công!',
                description: $result['message']
            );
            $this->closeUserModal();
            $this->resetPage(); // Refresh danh sách
        } else {
            $this->notification()->error(
                title: 'Lỗi!',
                description: $result['message']
            );
        }
    }

    public function render()
    {
        return view('auth::livewire.admin.user-management', [
            'users' => $this->users,
            'statistics' => $this->statistics,
            'roles' => $this->roles,
        ]);
    }
}
