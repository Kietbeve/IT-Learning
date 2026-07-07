<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

//Route trang dashboard
Route::get('/', [HomeController::class,'dashboard'])->name('home.dashboard');

// Route::get('/', function () {
//     return view('dashboard_user');
// });

// Route Admin - Quản lý Tags
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/tags', \App\Livewire\Admin\TagManagement::class)->name('admin.tags');
});

