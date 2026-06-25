<?php

namespace Modules\Auth\Services;

use Modules\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    // Service logic for authentication
    /**
     * Check admin credentials and log the user in if valid.
     *
     * @param  array  $credentials  ['name' => string, 'password' => string]
     * @return array ['success' => bool, 'message' => string|null, 'user' => ?\Modules\Auth\Models\User]
     */
    public function checkAdminLogin(array $credentials): array
    {    
        $user = User::where('name', $credentials['name'] ?? null)->first();

        if (! $user) {
            return [
                'success' => false,
                'message' => 'Tài khoản không tồn tại',
                'user' => null,
            ];
        }

        if (!Hash::check($credentials['password'] ?? '', $user->password)) {
            return [
                'success' => false,
                'message' => 'Email hoặc mật khẩu không đúng',
                'user' => null,
            ];
        }

        // ensure user has admin role (uses Spatie HasRoles)
        if (! method_exists($user, 'hasRole') || ! $user->hasRole('admin')) {
            return [
                'success' => false,
                'message' => 'Bạn không có quyền truy cập',
                'user' => null,
            ];
        }

        // log the user in
        Auth::login($user);

        return [
            'success' => true,
            'message' => null,
            'user' => $user,
        ];
    }

    /**
     * Update a user's public profile fields.
     *
     * @param  array{name?:string,phone?:string|null,bio?:string|null}  $data
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? null,
            'bio' => $data['bio'] ?? null,
        ]);

        return $user->fresh();
    }

    /**
     * Khóa tài khoản user với lý do
     * 
     * @param int $userId - ID user cần khóa
     * @param string $reason - Lý do khóa (bắt buộc)
     * @param int $blockedBy - ID admin thực hiện khóa
     * @return array ['success' => bool, 'message' => string, 'user' => User|null]
     */
    public function blockUser(int $userId, string $reason, int $blockedBy): array
    {
        // Validate: Không thể tự khóa chính mình
        if ($userId === $blockedBy) {
            return [
                'success' => false,
                'message' => 'Không thể tự khóa chính mình',
                'user' => null,
            ];
        }

        // Validate: Lý do khóa không được rỗng
        if (empty(trim($reason))) {
            return [
                'success' => false,
                'message' => 'Lý do khóa không được để trống',
                'user' => null,
            ];
        }

        $user = User::find($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy người dùng',
                'user' => null,
            ];
        }

        // Validate: User phải đang ở trạng thái active
        if ($user->status === 'blocked') {
            return [
                'success' => false,
                'message' => 'Người dùng đã bị khóa trước đó',
                'user' => null,
            ];
        }

        // Update trạng thái khóa
        $user->update([
            'status' => 'blocked',
            'blocked_reason' => $reason,
            'blocked_at' => now(),
            'blocked_by' => $blockedBy,
        ]);

        return [
            'success' => true,
            'message' => 'Đã khóa tài khoản thành công',
            'user' => $user->fresh(),
        ];
    }

    /**
     * Mở khóa tài khoản user
     * 
     * @param int $userId - ID user cần mở khóa
     * @return array ['success' => bool, 'message' => string, 'user' => User|null]
     */
    public function unblockUser(int $userId): array
    {
        $user = User::find($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy người dùng',
                'user' => null,
            ];
        }

        // Validate: User phải đang bị khóa
        if ($user->status !== 'blocked') {
            return [
                'success' => false,
                'message' => 'Người dùng không ở trạng thái bị khóa',
                'user' => null,
            ];
        }

        // Mở khóa và xóa thông tin khóa
        $user->update([
            'status' => 'active',
            'blocked_reason' => null,
            'blocked_at' => null,
            'blocked_by' => null,
        ]);

        return [
            'success' => true,
            'message' => 'Đã mở khóa tài khoản thành công',
            'user' => $user->fresh(),
        ];
    }

    /**
     * Lấy danh sách user với search, filter và sorting cho trang quản lý
     * 
     * @param array $filters - ['search' => string, 'role' => string, 'status' => string, 
     *                           'sortField' => string, 'sortDirection' => string, 'perPage' => int]
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUsersForManagement(array $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = User::query()->with(['roles']);

        // Search theo name hoặc email
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter theo role
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function ($q) use ($filters) {
                $q->where('name', $filters['role']);
            });
        }

        // Filter theo status
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Sorting
        $sortField = $filters['sortField'] ?? 'created_at';
        $sortDirection = $filters['sortDirection'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Eager load blocker relationship
        $query->with(['roles', 'blocker' => function ($q) {
            $q->select('id', 'name', 'email');
        }]);

        // Pagination
        $perPage = $filters['perPage'] ?? 15;
        return $query->paginate($perPage);
    }

    /**
     * Tính toán thống kê user cho dashboard
     * 
     * @return array ['total' => int, 'active' => int, 'blocked' => int, 
     *                'admin' => int, 'student' => int, 'contributor' => int]
     */
    public function getUserStatistics(): array
    {
        return [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'blocked' => User::where('status', 'blocked')->count(),
            'admin' => User::role('admin')->count(),
            'student' => User::role('student')->count(),
            'contributor' => User::role('contributor')->count(),
        ];
    }

    /**
     * Lấy chi tiết user với đầy đủ relationships cho modal chi tiết
     * 
     * @param int $userId
     * @return User|null
     */
    public function getUserDetail(int $userId): ?User
    {
        return User::with([
            'roles',
            'blocker' => function ($q) {
                $q->select('id', 'name', 'email', 'avatar');
            }
        ])->find($userId);
    }

    /**
     * Kiểm tra xem user hiện tại có thể khóa target user không
     * 
     * @param int $targetUserId - User cần khóa
     * @param int $currentUserId - User hiện tại
     * @return array ['canBlock' => bool, 'reason' => string|null]
     */
    public function canBlockUser(int $targetUserId, int $currentUserId): array
    {
        // Không thể tự khóa chính mình
        if ($targetUserId === $currentUserId) {
            return [
                'canBlock' => false,
                'reason' => 'Không thể tự khóa chính mình',
            ];
        }

        $targetUser = User::find($targetUserId);

        if (!$targetUser) {
            return [
                'canBlock' => false,
                'reason' => 'Không tìm thấy người dùng',
            ];
        }

        // Không thể khóa user đã bị khóa
        if ($targetUser->status === 'blocked') {
            return [
                'canBlock' => false,
                'reason' => 'Người dùng đã bị khóa',
            ];
        }

        return [
            'canBlock' => true,
            'reason' => null,
        ];
    }

    /**
     * Tạo user mới
     * 
     * @param array $data - ['name' => string, 'email' => string, 'password' => string|null, 
     *                        'roles' => array, 'phone' => string|null, 'bio' => string|null]
     * @return array ['success' => bool, 'message' => string, 'user' => User|null]
     */
    public function createUser(array $data): array
    {
        // Validate email unique
        if (User::where('email', $data['email'])->exists()) {
            return [
                'success' => false,
                'message' => 'Email đã tồn tại trong hệ thống',
                'user' => null,
            ];
        }

        // Validate: ít nhất 1 role (now accepts string, not just array)
        if (empty($data['roles'])) {
            return [
                'success' => false,
                'message' => 'Vui lòng chọn ít nhất một vai trò',
                'user' => null,
            ];
        }

        // Validate password cho admin và superadmin role (yêu cầu mật khẩu)
        $requiresPassword = in_array($data['roles'], ['admin', 'superadmin']);
        if ($requiresPassword && empty($data['password'])) {
            return [
                'success' => false,
                'message' => 'Admin và Superadmin role yêu cầu mật khẩu',
                'user' => null,
            ];
        }

        try {
            // Tạo user
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => !empty($data['password']) ? \Hash::make($data['password']) : null,
                'phone' => $data['phone'] ?? null,
                'bio' => $data['bio'] ?? null,
                'status' => 'active',
            ]);

            // Assign roles (wrap single role string in array)
            $user->syncRoles([$data['roles']]);

            return [
                'success' => true,
                'message' => 'Tạo người dùng thành công',
                'user' => $user->fresh(['roles']),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'user' => null,
            ];
        }
    }

    /**
     * Cập nhật thông tin user
     * 
     * @param int $userId - ID user cần cập nhật
     * @param array $data - ['name' => string, 'email' => string, 'password' => string|null, 
     *                        'roles' => array, 'phone' => string|null, 'bio' => string|null]
     * @return array ['success' => bool, 'message' => string, 'user' => User|null]
     */
    public function updateUser(int $userId, array $data): array
    {
        $user = User::find($userId);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Không tìm thấy người dùng',
                'user' => null,
            ];
        }

        // Validate email unique (trừ chính user đang edit)
        if (User::where('email', $data['email'])->where('id', '!=', $userId)->exists()) {
            return [
                'success' => false,
                'message' => 'Email đã tồn tại trong hệ thống',
                'user' => null,
            ];
        }

        // Validate: ít nhất 1 role (now accepts string, not just array)
        if (empty($data['roles'])) {
            return [
                'success' => false,
                'message' => 'Vui lòng chọn ít nhất một vai trò',
                'user' => null,
            ];
        }

        try {
            // Update user info
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'bio' => $data['bio'] ?? null,
            ];

            // Chỉ update password nếu có nhập mới
            if (!empty($data['password'])) {
                $updateData['password'] = \Hash::make($data['password']);
            }

            $user->update($updateData);

            // Sync roles (wrap single role string in array)
            $user->syncRoles([$data['roles']]);

            return [
                'success' => true,
                'message' => 'Cập nhật người dùng thành công',
                'user' => $user->fresh(['roles']),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage(),
                'user' => null,
            ];
        }
    }
}
