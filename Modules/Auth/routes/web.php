<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\GoogleController;
//
use Modules\Auth\Livewire\UserProfile;

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
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/profile', UserProfile::class)->middleware('auth')->name('auth.profile');
Route::get('/google', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');
//admin route
Route::get('/admin', [AuthController::class, 'adminDashboard'])->name('auth.admin.dashboard');
Route::get('/admin/login', [AuthController::class, 'adminLogin'])->name('auth.admin.login');
Route::post('/admin/login', [AuthController::class, 'checkAdminLogin'])->name('auth.admin.checkAdminLogin');
