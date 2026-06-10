<?php

use Illuminate\Support\Facades\Route;
use Modules\Learning\Http\Controllers\LearningController;

/*
|--------------------------------------------------------------------------
| Web Routes - Phân hệ Learning (Học tập)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. NHÓM PUBLIC (Không cần đăng nhập)
// ==========================================
// Trang danh sách lộ trình
Route::get('/roadmaps', function () {
    return 'Trang danh sách lộ trình'; 
})->name('learning.roadmaps.index');

Route::get('/roadmaps', function () {
    return view('learning::layouts.route-list');
});
// Trang chi tiết lộ trình (Chính là file roadmap-detail của bạn)
// Thay vì dùng Route::view, nên truyền tham số {id} động theo chuẩn sitemap
Route::get('/roadmaps/{id}', function ($id) {
    return view('learning::layouts.roadmap-detail', compact('id'));
})->name('learning.roadmaps.show');


// ==========================================
// 2. NHÓM STUDENT (Bắt buộc phải đăng nhập)
// ==========================================
// Sử dụng middleware 'auth' (hoặc middleware chặn quyền student của dự án bạn)
Route::middleware(['auth'])->group(function () {
    
    // Không gian học tập, làm bài học theo lộ trình (Chính là file lesson-view của bạn)
    Route::get('/roadmaps/{id}/learn', function ($id) {
        return view('learning::layouts.lesson-view', compact('id'));
    })->name('learning.roadmaps.learn');

});