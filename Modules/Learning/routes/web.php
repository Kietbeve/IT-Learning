<?php

use Illuminate\Support\Facades\Route;
use Modules\Learning\Http\Controllers\LearningController;

// Trang danh sách và chi tiết
Route::get('/roadmaps', [LearningController::class, 'index'])->name('learning.roadmaps.index');
Route::get('/roadmaps/{id}', [LearningController::class, 'show'])->name('learning.roadmaps.show');

// Nhóm yêu cầu đăng nhập (Nơi chứa route learn bị lỗi)
Route::middleware(['auth'])->group(function () {
    // CHÍNH LÀ DÒNG NÀY ĐÂY:
    Route::get('/roadmaps/{id}/learn/{lesson_id?}', [LearningController::class, 'learn'])->name('learning.roadmaps.learn');
    
    Route::post('/lessons/{lesson_id}/complete', [LearningController::class, 'completeLesson'])->name('learning.lessons.complete');
});