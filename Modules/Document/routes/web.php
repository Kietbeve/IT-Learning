<?php

use Illuminate\Support\Facades\Route;
use Modules\Document\Http\Controllers\DocumentDownloadController;
use Modules\Document\Http\Livewire\Admin\CategoryList;
use Modules\Document\Http\Livewire\Admin\DocumentReport;
use Modules\Document\Http\Livewire\Admin\DocumentUpload;
use Modules\Document\Http\Livewire\Contributor\Dashboard;
use Modules\Document\Http\Livewire\Contributor\DocumentEdit;
use Modules\Document\Http\Livewire\User\BookmarkedDocuments;
use Modules\Document\Http\Livewire\User\DocumentDetail;
use Modules\Document\Http\Livewire\User\DocumentList;

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
Route::get('/documents', DocumentList::class)->name('documents.index');
Route::get('/documents/download/{token}', [DocumentDownloadController::class, 'download'])->name('documents.download');
Route::get('/documents/{id}/{slug?}', DocumentDetail::class)->name('documents.show');

Route::middleware('auth')->group(function () {
    Route::get('/user/bookmarks', BookmarkedDocuments::class)->name('user.bookmarks');
});

// Admin Routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin']], function () {
    Route::get('/moderation/documents/{id}', \Modules\Document\Http\Livewire\Admin\DocumentDetail::class)->name('admin.moderation.documents.show');
    Route::get('/documents', \Modules\Document\Http\Livewire\Admin\DocumentList::class)->name('admin.documents.index');
    Route::get('/documents/create', \Modules\Document\Http\Livewire\Admin\DocumentUpload::class)->name('admin.documents.create');
    Route::get('/reports/documents', \Modules\Document\Http\Livewire\Admin\DocumentReport::class)->name('admin.reports.documents');
    Route::get('/categories/documents', \Modules\Document\Http\Livewire\Admin\CategoryList::class)->name('admin.categories.documents.index');
    Route::get('/subjects', \Modules\Document\Livewire\Admin\SubjectManagement::class)->name('admin.subjects');

});

// Contributor Routes
Route::group(['prefix' => 'contributor', 'middleware' => ['auth', 'role:contributor']], function () {
    Route::get('/dashboard', Dashboard::class)->name('contributor.dashboard');
    Route::get('/documents', Modules\Document\Http\Livewire\Contributor\DocumentList::class)->name('contributor.documents.index');
    Route::get('/documents/create', Modules\Document\Http\Livewire\Contributor\DocumentUpload::class)->name('contributor.documents.create');
    Route::get('/documents/{id}/edit', DocumentEdit::class)->name('contributor.documents.edit');
});

