<?php

use Illuminate\Support\Facades\Route;
// SỬA: Import đúng chuẩn Namespace của LearningController (có chữ app viết thường)
use Modules\Learning\Http\Controllers\LearningController;

/*
|--------------------------------------------------------------------------
| Web Routes - Phân hệ Learning (Học tập)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. NHÓM PUBLIC (Không cần đăng nhập)
// ==========================================

// Trang 1: Danh sách các lộ trình học tập (Hiển thị 6 ô ban đầu)
Route::get('/roadmaps', [LearningController::class, 'index'])
    ->name('learning.roadmaps.index');

// Trang 2: Chi tiết một lộ trình (Chứa danh sách 5 bài học của lộ trình đó)
Route::get('/roadmaps/{id}', [LearningController::class, 'show'])
    ->name('learning.roadmaps.show');

// Trang 3: Chi tiết một bài học cụ thể thuộc lộ trình
Route::get('/roadmaps/{roadmap_id}/lessons/{lesson_id}', [LearningController::class, 'showLesson'])
    ->name('learning.lessons.show');



// ==========================================
// 2. NHÓM STUDENT (Bắt buộc phải đăng nhập)
// ==========================================
Route::middleware(['auth'])->group(function () {
    
    // Không gian học tập, làm bài học theo lộ trình
    Route::get('/roadmaps/{id}/learn', function ($id) {
        return view('learning::layouts.lesson-view', compact('id'));
    })->name('learning.roadmaps.learn');

});