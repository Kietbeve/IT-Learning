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
});

//route auth
Route::group(["prefix"=> "exam","middleware"=> "auth"], function () {

});

//route auth + contributor
Route::group(["prefix"=> "contributor","middleware"=> ["auth",]], function () {
    Route::get('questions',[ExamController::class,"questionManager"])->name('contributor.questions');
    Route::get('exams',[ExamController::class,"examManager"])->name('contributor.exams');
    // Route::get('exams/{examId}/detail', [ExamController::class, 'examDetail'])->name('contributor.exams.detail');
    Route::get('exams/{examId}/detail', [ExamController::class,'examDetail'])->name('contributor.exams.detail');
});