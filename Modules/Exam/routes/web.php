<?php

use Illuminate\Support\Facades\Route;
use Modules\Exam\Http\Controllers\ExamController;

/*
|--------------------------------------------------------------------------
| Exam Module - Web Routes
|--------------------------------------------------------------------------
|
| Routes được tổ chức theo thứ tự quyền truy cập:
| 1. Public Routes - Không yêu cầu đăng nhập
| 2. Authenticated Routes - Yêu cầu đăng nhập
| 3. Contributor Routes - Dành cho người đóng góp nội dung
| 4. Admin Routes - Dành cho quản trị viên
|
*/

/*
|--------------------------------------------------------------------------
| Public Routes - Guest Access
|--------------------------------------------------------------------------
|
| Các route công khai cho người dùng chưa đăng nhập hoặc đã đăng nhập.
| Bao gồm: danh sách bài thi, chi tiết bài thi, làm bài, xem kết quả.
|
*/

Route::group(['prefix' => 'exam'], function () {
    // Danh sách tất cả bài thi
    Route::get('', [ExamController::class, 'index'])->name('exam.index');

    // Test routes - Giao diện tạm thời cho development
    Route::get('exam_attempt', [ExamController::class, 'examAttempt'])->name('exam.attempt');
    Route::get('exam_result', [ExamController::class, 'examResult'])->name('exam.result');

    // Làm bài thi - Livewire component
    Route::get('attempts/{attempt:session_id}', \Modules\Exam\Livewire\ExamAttemptPage::class)
        ->name('exam.attempt.take');

    // Xem kết quả bài thi đã làm
    Route::get('attempts/{attempt:session_id}/result', \Modules\Exam\Livewire\ExamResultPage::class)
        ->name('exam.attempt.result');
});

// Download template import câu hỏi
Route::get('/questions/import-template', [ExamController::class, 'template'])
    ->name('questions.import.template');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| Các route yêu cầu người dùng phải đăng nhập.
|
*/

Route::group(['prefix' => 'exam', 'middleware' => 'auth'], function () {
    // Xem danh sách kết quả các bài thi đã làm
    Route::get('results', Modules\Exam\Livewire\ResultList::class)->name('exam.results');
});

/*
|--------------------------------------------------------------------------
| Contributor Routes
|--------------------------------------------------------------------------
|
| Các route dành cho người đóng góp nội dung (Contributor).
| Bao gồm: quản lý câu hỏi, quản lý bài thi, xem bài làm của học viên.
|
*/

Route::group(['prefix' => 'contributor', 'middleware' => ['auth']], function () {
    // Quản lý câu hỏi - Danh sách câu hỏi của contributor
    Route::get('questions', [ExamController::class, 'questionManager'])->name('contributor.questions');

    // Quản lý bài thi
    Route::get('exams', [ExamController::class, 'examManager'])->name('contributor.exams');
    Route::get('exams/{examId}/detail', [ExamController::class, 'examDetail'])->name('contributor.exams.detail');
    Route::get('exams/{examId}/questions', [ExamController::class, 'examQuestionManager'])->name('contributor.exams.questions');

    // Quản lý bài làm của học viên
    Route::get('exams/{examId}/attempts', [ExamController::class, 'examAttemptManager'])->name('contributor.exams.attempts');
    Route::get('exams/attempts/{attemptId}/answers', [ExamController::class, 'attemptAnswerDetail'])->name('contributor.exams.attempts.answer');
    
    // Chấm điểm và hoàn thành bài thi
    Route::post('exams/attempts/{attemptId}/finalize', [ExamController::class, 'finalizeAttempt'])->name('contributor.exams.attempts.finalize');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Các route dành cho quản trị viên (Admin).
| Bao gồm: duyệt bài thi, quản lý câu hỏi toàn hệ thống.
|
*/

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    // Duyệt và phê duyệt bài thi
    Route::get('moderation/exams', [ExamController::class, 'examReviewTable'])->name('admin.moderation.exam');
    Route::get('exams/{exam}/review', \Modules\Exam\Livewire\Admin\ExamReviewPage::class)->name('admin.review.exam.detail');

    // Quản lý tất cả câu hỏi trong hệ thống
    Route::get('questions', [ExamController::class, 'questionManager'])->name('admin.questions');
});

/*
|--------------------------------------------------------------------------
| Dynamic Exam Routes (Must be last)
|--------------------------------------------------------------------------
|
| Routes sử dụng slug động để hiển thị chi tiết bài thi.
| ⚠️ QUAN TRỌNG: Phải đặt ở cuối cùng để tránh conflict với các route khác.
| Nếu đặt ở trên, "exam/anything" sẽ bị match vào route này thay vì các 
| route cụ thể như "exam/exam_attempt".
|
*/

// Chi tiết bài thi theo slug
Route::get('exam/{examSlug}', [ExamController::class, 'showExamDetail'])->name('exam.examDetail');

// Bắt đầu làm bài thi
Route::post('exam/{examSlug}', [ExamController::class, 'startExam'])->name('exam.attempt.start');