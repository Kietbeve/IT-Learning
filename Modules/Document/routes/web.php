<?php

use Illuminate\Support\Facades\Route;
use Modules\Document\Http\Controllers\DocumentController;

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

// User Routes
Route::get('/documents', \Modules\Document\Http\Livewire\User\DocumentList::class)->name('documents.index');
Route::get('/documents/{id}', \Modules\Document\Http\Livewire\User\DocumentDetail::class)->name('documents.show');
Route::get('/documents/download/{token}', [\Modules\Document\Http\Controllers\DocumentDownloadController::class, 'download'])->name('documents.download');

Route::middleware('auth')->group(function () {
    Route::get('/student/bookmarks', \Modules\Document\Http\Livewire\User\BookmarkedDocuments::class)->name('student.bookmarks');
    Route::get('/purchases', \Modules\Document\Http\Livewire\User\PurchasedDocuments::class)->name('purchases.index');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/moderation/documents', \Modules\Document\Http\Livewire\Admin\DocumentModeration::class)->name('admin.moderation.documents.index');
    Route::get('/moderation/documents/{id}', \Modules\Document\Http\Livewire\Admin\DocumentDetail::class)->name('admin.moderation.documents.show');
    Route::get('/documents', \Modules\Document\Http\Livewire\Admin\DocumentList::class)->name('admin.documents.index');
    Route::get('/reports/documents', \Modules\Document\Http\Livewire\Admin\DocumentReport::class)->name('admin.reports.documents');
    Route::get('/categories/documents', \Modules\Document\Http\Livewire\Admin\CategoryList::class)->name('admin.categories.documents.index');
});

