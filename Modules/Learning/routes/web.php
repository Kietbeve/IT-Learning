<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

// Các Controller và Component
use Modules\Learning\Http\Controllers\LearningController;
use Modules\Learning\Livewire\Manage\RoadmapManagement;
use Modules\Learning\Livewire\Manage\ManagementDetail;
use Modules\Learning\Livewire\Manage\ManagementLesson;
use Modules\Learning\Livewire\Admin\Collaborators\Index as CollabIndex;
use Modules\Learning\Livewire\Admin\Collaborators\Create as CollabCreate;
use Modules\Learning\Livewire\Admin\Collaborators\Edit as CollabEdit;

/*
|--------------------------------------------------------------------------
| Livewire Component Registration
|--------------------------------------------------------------------------
*/
Livewire::component('modules.learning.livewire.admin.collaborators', CollabIndex::class);
Livewire::component('modules.learning.livewire.admin.collaborators.create', CollabCreate::class);
Livewire::component('modules.learning.livewire.admin.collaborators.edit', CollabEdit::class);

/*
|--------------------------------------------------------------------------
| Admin Collaborator Routes
|--------------------------------------------------------------------------
*/
Route::get('/admin/collaborators', CollabIndex::class);
Route::get('/admin/collaborators/create', CollabCreate::class);
Route::get('/admin/collaborators/edit/{id}', CollabEdit::class);


/*
|--------------------------------------------------------------------------
| Web Routes - Phân hệ Learning (Học tập)
|--------------------------------------------------------------------------
*/

// 1. NHÓM PUBLIC
Route::get('/roadmaps', [LearningController::class, 'index'])->name('learning.roadmaps.index');
Route::get('/roadmaps/{id}', [LearningController::class, 'show'])->name('learning.roadmaps.show');
Route::get('/roadmaps/{roadmap_id}/lessons/{lesson_id}', [LearningController::class, 'showLesson'])->name('learning.lessons.show');


// 2. NHÓM STUDENT
Route::middleware(['auth'])->group(function () {
    Route::get('/roadmaps/{roadmapId}/learn/{lessonId?}', [LearningController::class, 'learn'])->name('learning.roadmaps.learn');
    Route::post('/roadmaps/{id}/enroll', [LearningController::class, 'enroll'])->name('learning.roadmaps.enroll');
    Route::post('/roadmaps/{roadmap_id}/lessons/{lesson_id}/complete', [LearningController::class, 'completeLesson'])->name('learning.lessons.complete');
    Route::post('/lessons/{lesson_id}/note', [LearningController::class, 'saveNote'])->name('learning.lessons.note');
    Route::post('/lessons/{lesson_id}/question', [LearningController::class, 'postQuestion'])->name('learning.lessons.question');
    Route::delete('/questions/{question_id}', [LearningController::class, 'destroyQuestion'])->name('learning.questions.destroy');
    Route::post('/roadmaps/{roadmap_id}/lessons/{lesson_id}/submit-project', [LearningController::class, 'submitProject'])->name('learning.roadmaps.lessons.submit-project');
});


// 3. NHÓM ADMIN
Route::middleware(['auth'])->prefix('admin/learning')->name('admin.learning.')->group(function () {
    Route::get('/submissions', \Modules\Learning\Livewire\Admin\ProjectSubmissionList::class)->name('submissions.index');
    Route::get('/submissions/{submissionId}/review', \Modules\Learning\Livewire\Admin\ProjectSubmissionReview::class)->name('submissions.review');
});


// 4. NHÓM MANAGE
Route::prefix('manage')->group(function () {
    Route::get('/', function () {
        return view('learning::manage.management-dashboard');
    })->name('manage.dashboard');

    Route::get('/roadmap', RoadmapManagement::class)->name('manage.roadmap');
    Route::get('/detail', ManagementDetail::class)->name('manage.detail');
    
    Route::get('/lesson', function () {
        return redirect()->route('manage.detail');
    })->name('manage.lesson');

    Route::get('/lessons/{section_id}', ManagementLesson::class)->name('management.lessons.index');
    
    Route::get('/sections', function () {
        return view('learning::manage.management-section');
    })->name('management.sections.index');
});