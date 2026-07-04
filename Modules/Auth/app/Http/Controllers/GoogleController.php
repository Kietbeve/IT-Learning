<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Auth\Services\GoogleService;

class GoogleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function redirect(GoogleService $service, Request $request)
    {
        // Lưu session_id của attempt để redirect sau khi login (nếu có)
        if ($request->has('exam_session')) {
            session(['exam_session_after_login' => $request->get('exam_session')]);
        }

        // Lưu popup mode vào session (để callback biết cần trả HTML thay vì redirect)
        if ($request->has('popup')) {
            session(['is_popup_login' => true]);
        }

        return $service->redirect($request);
    }

    public function callback(GoogleService $service, Request $request)
    {
        // Call service để xử lý OAuth, login và link attempts
        // Service chỉ trả về data, Controller xử lý response
        $result = $service->callback($request);
        
        // Kiểm tra popup mode
        $isPopup = session()->pull('is_popup_login');
        $examSession = session()->pull('exam_session_after_login');
        
        if ($isPopup) {
            // Popup mode: render Blade view với postMessage script
            return view('auth::popup-success', [
                'linkedCount' => $result['linkedCount']
            ]);
        }
        
        if ($examSession) {
            // Exam mode: redirect về trang làm bài
            $message = "Đăng nhập thành công!";
            if ($result['linkedCount'] > 0) {
                $message .= " Đã liên kết {$result['linkedCount']} bài thi.";
            }
            return redirect()->route('exam.attempt.take', ['attempt' => $examSession])
                ->with('success', $message);
        }
        
        // Default: redirect về trang chủ
        return redirect('/');
    }
}
