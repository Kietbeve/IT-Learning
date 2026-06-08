<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Models\User;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
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

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
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

        return redirect()->route('login');
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
            return redirect()->route('auth.admin.dashboard');
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

        $result = $authService->checkAdminLogin(request()->only('name', 'password'));

        if (! $result['success']) {
            return redirect()->back()->withErrors(['login_error' => $result['message']])->withInput();
        }

        return redirect()->route('auth.admin.dashboard');
    }
}
