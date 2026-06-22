<?php

use Illuminate\Support\Facades\Route;
use Modules\Exam\Http\Controllers\ExamController;
use Modules\Exam\Livewire\ExamDetail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//route guest
Route::group(["prefix"=> "exam"], function () {
    // Route::resource('exam', ExamController::class)->names('exam');
    Route::get("", [ExamController::class, 'index'])->name('exam.index');

    //Route test giao diện trang bai thi
    Route::get("exam_attempt", [ExamController::class, 'examAttempt'])->name('exam.attempt');

    //Route test giao diện trang ket qua bai thi
    Route::get("exam_result", [ExamController::class, 'examResult'])->name('exam.result');

    // Exam attempt page - làm bài thi
    Route::get('attempts/{attempt:session_id}', \Modules\Exam\Livewire\ExamAttemptPage::class)
        ->name('exam.attempt.take');

    // Exam result page - xem kết quả bài thi
    Route::get('attempts/{attempt:session_id}/result', \Modules\Exam\Livewire\ExamResultPage::class)
        ->name('exam.attempt.result');
});

//route auth
Route::group(["prefix"=> "exam","middleware"=> "auth"], function () {
    Route::get('results',Modules\Exam\Livewire\ResultList::class)->name('exam.results');
});

//route auth + contributor
Route::group(["prefix"=> "contributor","middleware"=> ["auth",]], function () {
//Route::group(["prefix"=> "contributor","middleware"=> ["auth","role:admin|contributor"] ], function () {//test role
    Route::get('questions',[ExamController::class,"questionManager"])->name('contributor.questions');
    Route::get('exams',[ExamController::class,"examManager"])->name('contributor.exams');
    // Route::get('exams/{examId}/detail', [ExamController::class, 'examDetail'])->name('contributor.exams.detail');
    Route::get('exams/{examId}/detail', [ExamController::class,'examDetail'])->name('contributor.exams.detail');
    route::get('exams/{examId}/questions',[ExamController::class,"examQuestionManager"])->name('contributor.exams.questions');
    Route::get('exams/{examId}/attempts',[ExamController::class,"examAttemptManager"])->name('contributor.exams.attempts');
    Route::get('exams/attempts/{attemptId}/answers',[ExamController::class,"attemptAnswerDetail"])->name('contributor.exams.attempts.answer');
});

Route::group(["prefix"=> "admin","middleware"=> ["auth","role:admin"] ], function () {//test role
    Route::get('exams/{exam}/review', \Modules\Exam\Livewire\Admin\ExamReviewPage::class)->name('admin.review.exam.detail');
    Route::get('exams',[ExamController::class,'examReviewTable'])->name('admin.review.exam');
});

// Route giao diện Exam Detail bằng slug | Đặt ở cuối vì bị sung đột với các route khác- gõ exam/bất kì đều vào route này
Route::get('exam/{examSlug}', [ExamController::class, 'showExamDetail'])->name('exam.examDetail');