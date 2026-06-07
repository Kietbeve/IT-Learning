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
}
