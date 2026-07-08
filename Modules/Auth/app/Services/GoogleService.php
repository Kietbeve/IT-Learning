<?php
namespace Modules\Auth\Services;

use Laravel\Socialite\Facades\Socialite;
use Modules\Auth\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Modules\Exam\Models\ExamAttempt;

class GoogleService
{
    public function redirect(Request $request = null)
    {
        // Dùng callback URL mặc định (đã đăng ký trong Google Console)
        // Popup mode được xác định qua session, không cần thay đổi URL
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

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

        // Link các exam attempts từ cookies với user vừa đăng nhập
        $linkedCount = $this->linkExamAttemptsFromCookies($user, $request);

        // Trả về data để Controller xử lý response (popup view hoặc redirect)
        return [
            'user' => $user,
            'linkedCount' => $linkedCount
        ];
    }

    /**
     * Liên kết các exam attempts từ cookies với user sau khi đăng nhập
     * 
     * @param User $user User vừa đăng nhập
     * @param Request $request Request chứa cookies
     * @return int Số lượng attempts đã được liên kết
     */
    public function linkExamAttemptsFromCookies(User $user, Request $request): int
    {
        $linkedCount = 0;

        // Lấy cookie guest_exam_attempts
        $attempts = json_decode(
            $request->cookie('guest_exam_attempts', '{}'),
            true
        );

        $attempts = is_array($attempts) ? $attempts : [];

        // Lấy tất cả session_id từ cookie
        $sessionIds = collect($attempts)
            ->flatten()
            ->unique()
            ->values()
            ->all();

        foreach ($sessionIds as $sessionId) {
            $attempt = ExamAttempt::query()
                ->where('session_id', $sessionId)
                ->whereNull('user_id')
                ->first();

            if ($attempt) {
                $attempt->update([
                    'user_id' => $user->id,
                ]);

                $linkedCount++;
            }
        }
        
        return $linkedCount;
    }
}