<?php
namespace Modules\Auth\Services;

use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\Auth;

class GoogleService
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Kiểm tra tài khoản đã tồn tại chưa
        $existingUser = User::where('email', $googleUser->email)->first();

        if ($existingUser) {
            // Tài khoản đã tồn tại: chỉ cập nhật thông tin Google
            $existingUser->update([
                'google_id' => $googleUser->id,
                'avatar'    => $googleUser->avatar,
            ]);
            $user = $existingUser;
        } else {
            // Tài khoản chưa tồn tại: tạo mới và gán role student
            $user = User::create([
                'name'      => $googleUser->name,
                'email'     => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar'    => $googleUser->avatar,
            ]);
            $user->assignRole('student');
        }

        Auth::login($user);

        return redirect('/exam');
    }
}