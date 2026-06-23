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
    
    // Không gian học tập - Learning workspace với lessonId tùy chọn
    Route::get('/roadmaps/{roadmapId}/learn/{lessonId?}', [LearningController::class, 'learn'])
        ->name('learning.roadmaps.learn');

    // Đăng ký vào lộ trình
    Route::post('/roadmaps/{id}/enroll', [LearningController::class, 'enroll'])
        ->name('learning.roadmaps.enroll');

    // Đánh dấu bài học hoàn thành và chuyển sang bài tiếp theo
    Route::post('/roadmaps/{roadmap_id}/lessons/{lesson_id}/complete', [LearningController::class, 'completeLesson'])
        ->name('learning.lessons.complete');

    // Lưu ghi chú bài học
    Route::post('/lessons/{lesson_id}/note', [LearningController::class, 'saveNote'])
        ->name('learning.lessons.note');

    // Đặt câu hỏi thảo luận
    Route::post('/lessons/{lesson_id}/question', [LearningController::class, 'postQuestion'])
        ->name('learning.lessons.question');

    // SỬA/THÊM: Route xóa bình luận câu hỏi thảo luận
    Route::delete('/questions/{question_id}', [LearningController::class, 'destroyQuestion'])
        ->name('learning.questions.destroy');

    // Nộp dự án (project submission)
    Route::post('/roadmaps/{roadmap_id}/lessons/{lesson_id}/submit-project', [LearningController::class, 'submitProject'])
        ->name('learning.lessons.submitProject');

});