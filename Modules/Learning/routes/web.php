<?php

use Illuminate\Support\Facades\Route;
use Modules\Learning\Http\Controllers\LearningController;

// ==========================================
// 1. NHÓM PUBLIC (Không cần đăng nhập)
// ==========================================
// Trang danh sách và chi tiết lộ trình (Đã nối với Controller chuẩn)
Route::get('/roadmaps', [LearningController::class, 'index'])->name('learning.roadmaps.index');
Route::get('/roadmaps/{id}', [LearningController::class, 'show'])->name('learning.roadmaps.show');

// ==========================================
// 2. NHÓM STUDENT (Bắt buộc phải đăng nhập)
// ==========================================
Route::middleware(['auth'])->group(function () {
    Route::get('/roadmaps/{id}/learn/{lesson_id?}', [LearningController::class, 'learn'])->name('learning.roadmaps.learn');
    Route::post('/lessons/{lesson_id}/complete', [LearningController::class, 'completeLesson'])->name('learning.lessons.complete');
});