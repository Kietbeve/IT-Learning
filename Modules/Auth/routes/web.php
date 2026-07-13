<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\GoogleController;
//
use Modules\Auth\Livewire\UserProfile;
use Modules\Auth\Livewire\CtvRegistration;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([], function () {
    Route::resource('auth', AuthController::class)->names('auth');
});
Route::get("/login", [AuthController::class, 'UserLogin'])->name('login');
Route::get('/dashboard', [AuthController::class, 'dashboard'])->middleware('auth')->name('auth.dashboard');
Route::get('/profile', [AuthController::class, 'profile'])->middleware('auth')->name('auth.profile');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/profile', UserProfile::class)->middleware('auth')->name('auth.profile');
Route::get('/ctv/register', CtvRegistration::class)->middleware('auth')->name('auth.ctv.register');
Route::get('/google', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
//admin route
// Route::get('/admin', [AuthController::class, 'adminDashboard'])->name('auth.admin.dashboard');
Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('auth.admin.login');
Route::post('/admin/login', [AuthController::class, 'checkAdminLogin'])->name('auth.admin.checkAdminLogin');
Route::post('/admin/logout', [AuthController::class, 'adminLogout'])->name('auth.admin.logout');
//admin CTV routes
Route::prefix('admin/ctv')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', \Modules\Auth\Livewire\Admin\AdminCtvApplications::class)->name('admin.ctv.list');
    Route::get('/{id}', \Modules\Auth\Livewire\Admin\AdminCtvDetail::class)->name('admin.ctv.detail');
});
Route::get('admin/users', \Modules\Auth\Livewire\Admin\UserManagement::class)
    ->middleware(['auth', 'role:admin'])
    ->name('admin.users');
