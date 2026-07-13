<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\Auth\Services\AuthService;

class AuthController extends Controller
{
    /**
     * Connect to the auth API.
     */
    public function connect(Request $request)
    {
        return response()->json([
            'status' => 'connected',
            'message' => 'Auth API connected successfully.',
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('auth::index');
    }


    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('auth::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('auth::edit');
    }

    function UserLogin()
    {
        if (Auth::check()) {
            return redirect()->route('auth.dashboard');
        }
        return view('auth::login');
    }

    public function dashboard()
    {
        return view('auth::dashboard');
    }

    public function profile()
    {
        return view('auth::profile');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // return redirect()->route('login');
        return redirect('/');
    }
    //admin function
    public function adminDashboard()
    {
        return view('auth::admin.dashboard');
    }
    public function adminLogin()
    {
        $user = User::find(Auth::id());
        if (Auth::check() && $user?->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth::admin.login');
    }
    public function adminLogout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.admin.login');
    }
    public function checkAdminLogin()
    {
        $authService = new AuthService();

        $result = $authService->checkAdminLogin(request()->only('email', 'password'));

        if (! $result['success']) {
            return redirect()->back()->withErrors(['login_error' => $result['message']])->withInput();
        }

        return redirect()->route('admin.dashboard');
    }
}
