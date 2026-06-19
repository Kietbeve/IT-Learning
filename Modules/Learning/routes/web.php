<?php

use Illuminate\Support\Facades\Route;
use Modules\Learning\Http\Controllers\LearningController;

// Đưa URL về thẳng /roadmaps để hiển thị đúng giao diện bạn đang truy cập
Route::prefix('roadmaps')->name('learning.roadmaps.')->group(function () {
    Route::get('/', [LearningController::class, 'index'])->name('index');
    Route::get('/{id}', [LearningController::class, 'show'])->name('show');
    
    // Các tác vụ yêu cầu đăng nhập được xử lý an toàn tại Route Level
    Route::middleware('auth')->group(function () {
        Route::post('/{id}/enroll', [LearningController::class, 'enroll'])->name('enroll');
        Route::get('/{roadmapId}/learn/{lessonId?}', [LearningController::class, 'learn'])->name('learn');
        Route::post('/{roadmapId}/complete/{lessonId}', [LearningController::class, 'completeLesson'])->name('complete');
    });
});

// Nhóm Route tương tác bài học
Route::middleware('auth')->prefix('learning/lessons')->name('learning.lessons.')->group(function () {
    Route::post('/{lessonId}/note', [LearningController::class, 'saveNote'])->name('note');
    Route::post('/{lessonId}/question', [LearningController::class, 'postQuestion'])->name('question');
});