<?php

namespace Modules\Auth\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\Auth\Models\User;
use Modules\Auth\Services\AuthService;
use WireUi\Traits\WireUiActions;

/**
 * Livewire Component: UserProfile
 *
 * Quản lý hồ sơ cá nhân người dùng.
 * Cho phép xem và cập nhật: name, phone, bio.
 * Email & google_id ở chế độ readonly.
 */
class UserProfile extends Component
{
    use WireUiActions;
    protected AuthService $authService;

    public function boot(AuthService $authService): void
    {
        $this->authService = $authService;
    }

    // --- Form fields ---
    public string $name  = '';
    public string $email = '';
    public string $phone = '';
    public string $bio   = '';

    // --- Display-only fields ---
    public ?string $google_id          = null;
    public ?string $avatar             = null;
    public string  $status             = 'active';
    public ?string $blocked_reason     = null;
    public ?string $blocked_at         = null;
    public float   $contributor_balance = 0;
    public array   $roles              = [];

    /**
     * Khởi tạo component, load dữ liệu từ user đang đăng nhập.
     */
    public function mount(): void
    {
        /** @var User $user */
        $user = Auth::user();

        $this->name               = $user->name ?? '';
        $this->email              = $user->email ?? '';
        $this->phone              = $user->phone ?? '';
        $this->bio                = $user->bio ?? '';
        $this->google_id          = $user->google_id;
        $this->avatar             = $user->avatar;
        $this->status             = $user->status ?? 'active';
        $this->blocked_reason     = $user->blocked_reason;
        $this->blocked_at         = $user->blocked_at
            ? \Carbon\Carbon::parse($user->blocked_at)->format('d/m/Y H:i')
            : null;
        $this->contributor_balance = (float) ($user->contributor_balance ?? 0);
        $this->roles              = $user->getRoleNames()->values()->toArray();
    }

    /**
     * Validation rules.
     */
    protected function rules(): array
    {
        return [
            'name'  => ['required', 'string', 'min:3', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom validation messages (tiếng Việt).
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'Họ và tên không được để trống.',
            'name.min'      => 'Họ và tên phải có ít nhất 3 ký tự.',
            'name.max'      => 'Họ và tên không được vượt quá 255 ký tự.',
            'phone.max'     => 'Số điện thoại không được vượt quá 20 ký tự.',
            'bio.max'       => 'Tiểu sử không được vượt quá 1000 ký tự.',
        ];
    }

    /**
     * Lưu thông tin cá nhân.
     */
    public function save(): void
    {
        $validated = $this->validate();

        /** @var User $user */
        $user = Auth::user();
        $updatedUser = $this->authService->updateProfile($user, $validated);
        $this->name = $updatedUser->name;

        $this->notification()->success(
            title: 'Thành công!',
            description: 'Cập nhật hồ sơ thành công.'
        );
    }

    /**
     * Placeholder — avatar chỉ hiển thị, không upload.
     */
    public function uploadAvatar(): void
    {
        // Avatar upload không được hỗ trợ trong phiên bản này.
    }

    public function render()
    {
        return view('auth::livewire.user-profile')->extends('layouts.user');
    }
}
