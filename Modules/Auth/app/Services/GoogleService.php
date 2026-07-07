<?php
namespace Modules\Auth\Services;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
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
            // Kiểm tra trạng thái bị khóa
            if ($existingUser->status === 'blocked') {
                return redirect('/login')->withErrors(['error' => 'Tài khoản của bạn đã bị khóa. ' . $existingUser->blocked_reason]);
            }
            
            // Tài khoản đã tồn tại: chỉ cập nhật thông tin Google
            $existingUser->update([
                'google_id' => $googleUser->id,
                'avatar'    => $googleUser->avatar,
            ]);
            $user = $existingUser;

            if ($user->roles()->count() === 0) {
                $user->assignRole('user');
            }
        } else {
            // Tài khoản chưa tồn tại: tạo mới và gán role user
            $user = User::create([
                'name'      => $googleUser->name,
                'email'     => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar'    => $googleUser->avatar,
            ]);
            $user->assignRole('user');
        }

        // Cập nhật lịch sử đăng nhập
        $user->update([
            'last_login_at' => now(),
            'last_login_IP' => request()->ip(),
        ]);

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

        // Lấy tất cả cookies
        $cookies = $request->cookies->all();

        // Duyệt qua từng cookie để tìm pattern exam_{exam_id}
        foreach ($cookies as $key => $value) {
            // Kiểm tra cookie có pattern exam_{exam_id} không
            if (preg_match('/^exam_(\d+)$/', $key, $matches)) {
                $sessionId = $value;

                // Tìm attempt với session_id này và chưa có user_id
                $attempt = ExamAttempt::query()
                    ->where('session_id', $sessionId)
                    ->whereNull('user_id')
                    ->first();

                // Nếu tìm thấy attempt chưa có user_id thì gán user_id
                if ($attempt) {
                    $attempt->update(['user_id' => $user->id]);
                    $linkedCount++;
                }
            }
        }

        return $linkedCount;
    }
}