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

        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                // 'provider' => 'google', ko co luu provoider
            ]
        );

        Auth::login($user);

       // return redirect('/dashboard');
        return redirect('/exam');
    }
}