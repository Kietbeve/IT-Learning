<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Controllers\GoogleController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    // Tự động tạo các API route:
    // GET    /api/v1/auth        -> index
    // POST   /api/v1/auth        -> store
    // GET    /api/v1/auth/{id}   -> show
    // PUT    /api/v1/auth/{id}   -> update
    // DELETE /api/v1/auth/{id}   -> destroy
    Route::apiResource('auth', AuthController::class)->names('auth');
});
Route::get('/', [AuthController::class, 'connect'])->name('auth.connect');
