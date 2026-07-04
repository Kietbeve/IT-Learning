<?php

use Illuminate\Support\Facades\Route;
use Modules\Learning\Http\Controllers\LearningController;
use Livewire\Livewire;
use Modules\Learning\Livewire\Manage\RoadmapManagement;
use Modules\Learning\Livewire\Admin\Collaborators\Index as CollabIndex;
use Modules\Learning\Livewire\Admin\Collaborators\Create as CollabCreate;
use Modules\Learning\Livewire\Admin\Collaborators\Edit as CollabEdit;

// Registered Livewire Components
Livewire::component('modules.learning.livewire.admin.collaborators', CollabIndex::class);
Livewire::component('modules.learning.livewire.admin.collaborators.create', CollabCreate::class);
Livewire::component('modules.learning.livewire.admin.collaborators.edit', CollabEdit::class);
Livewire::component('modules.learning.livewire.forum.thread-list', \Modules\Learning\Livewire\Forum\ThreadList::class);
Livewire::component('modules.learning.livewire.forum.create-thread', \Modules\Learning\Livewire\Forum\CreateThread::class);
Livewire::component('modules.learning.livewire.forum.thread-detail', \Modules\Learning\Livewire\Forum\ThreadDetail::class);
Livewire::component('modules.learning.livewire.forum.forum-index', \Modules\Learning\Livewire\Forum\ForumIndex::class);

Route::get('/admin/collaborators', CollabIndex::class);
Route::get('/admin/collaborators/create', CollabCreate::class);
Route::get('/admin/collaborators/edit/{id}', CollabEdit::class);


/*
|--------------------------------------------------------------------------
| Web Routes - Phân hệ Learning (Học tập)
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. NHÓM PUBLIC (Không cần đăng nhập)
// ==========================================

// Trang 1: Danh sách các lộ trình học tập
Route::get('/roadmaps', [LearningController::class, 'index'])
    ->name('learning.roadmaps.index');

// Trang 2: Chi tiết một lộ trình
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

    // Route xóa bình luận câu hỏi thảo luận
    Route::delete('/questions/{question_id}', [LearningController::class, 'destroyQuestion'])
        ->name('learning.questions.destroy');

    // Nộp dự án (project submission)
    Route::post('/roadmaps/{roadmap_id}/lessons/{lesson_id}/submit-project', [LearningController::class, 'submitProject'])
        ->name('learning.roadmaps.lessons.submit-project');
});


// ==========================================
// 3. NHÓM FORUM (Thảo luận - Guest có thể xem)
// ==========================================
Route::prefix('forum')->name('learning.forum.')->group(function () {

    // Trang diễn đàn tổng
    Route::get('/', \Modules\Learning\Livewire\Forum\ForumIndex::class)
        ->name('index');

    // Trang diễn đàn theo roadmap cụ thể
    Route::get('/roadmap/{id}', \Modules\Learning\Livewire\Forum\ForumIndex::class)
        ->name('roadmap');

    // Danh sách thread theo type (roadmap/lesson)
    Route::get('/{type}/{id}', function ($type, $id) {
        return view('learning::forum.index', compact('type', 'id'));
    })->name('threads.index');

    // Xem chi tiết thread
    Route::get('/thread/{threadId}', \Modules\Learning\Livewire\Forum\ThreadDetail::class)
        ->name('threads.show');

    // Tạo thread mới (cần đăng nhập)
    Route::middleware(['auth'])->group(function () {
        Route::get('/{type}/{id}/create', \Modules\Learning\Livewire\Forum\CreateThread::class)
            ->name('threads.create');
    });
});


// ==========================================
// 5. NHÓM ADMIN (Quản lý submissions + forum)
// ==========================================
Route::middleware(['auth'])->prefix('admin/learning')->name('admin.learning.')->group(function () {
    
    // Danh sách tất cả submissions
    Route::get('/submissions', \Modules\Learning\Livewire\Admin\ProjectSubmissionList::class)
        ->name('submissions.index');
    
    // Review chi tiết một submission
    Route::get('/submissions/{submissionId}/review', \Modules\Learning\Livewire\Admin\ProjectSubmissionReview::class)
        ->name('submissions.review');

    // Quản lý diễn đàn
    Route::get('/forum', \Modules\Learning\Livewire\Admin\ForumManagement::class)
        ->name('forum');
});


// ==========================================
// 6. NHÓM MANAGE (Quản lý nội dung lộ trình)
// ==========================================
Route::get('/manage', function () {
    return view('learning::manage.management-dashboard');
});

// ĐÃ SỬA: Chỉ giữ lại duy nhất route này cho Roadmap để chạy qua Livewire Component thực tế
Route::get('/manage/roadmap', RoadmapManagement::class)->name('manage.roadmap');

// Route cho trang Chi tiết/Danh sách bài học
Route::get('/manage/detail', function () {
    return view('learning::manage.namagement-detail');
})->name('manage.detail');

// Route cho trang Quản lý Chi tiết bài học
Route::get('/manage/lesson', function () {
    return view('learning::manage.management-lesson');
})->name('manage.lesson');