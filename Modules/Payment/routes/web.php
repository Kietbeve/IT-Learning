<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\PaymentController;

Route::middleware(['auth'])->group(function () {
    Route::get('/student/purchases', \Modules\Payment\Http\Livewire\User\Purchases::class)->name('student.purchases');
    Route::get('/student/transactions', \Modules\Payment\Http\Livewire\User\TransactionHistory::class)->name('student.transactions');
    Route::get('/student/subscription', \Modules\Payment\Http\Livewire\User\Subscription::class)->name('student.subscription');
});

Route::get('/payment/return', [PaymentController::class, 'return'])->name('payment.return');
Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::get('/payment/failed', [PaymentController::class, 'failed'])->name('payment.failed');

Route::middleware(['auth'])->prefix('contributor')->group(function () {
    Route::get('/transactions', \Modules\Payment\Http\Livewire\Contributor\TransactionHistory::class)->name('contributor.transactions');
    Route::get('/payout-request', \Modules\Payment\Http\Livewire\Contributor\PayoutRequest::class)->name('contributor.payout-request');
    Route::get('/earnings', \Modules\Payment\Http\Livewire\Contributor\EarningsReport::class)->name('contributor.earnings');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/', \Modules\Payment\Http\Livewire\Admin\PlatformRevenue::class)->name('admin.dashboard');
    Route::get('/orders', \Modules\Payment\Http\Livewire\Admin\OrderManagement::class)->name('admin.orders.index');
    Route::get('/transactions', \Modules\Payment\Http\Livewire\Admin\TransactionList::class)->name('admin.transactions.index');
    Route::get('/payouts/review', \Modules\Payment\Http\Livewire\Admin\PayoutReview::class)->name('admin.payouts.review');
    Route::get('/settings/revenue', \Modules\Payment\Http\Livewire\Admin\RevenueSettings::class)->name('admin.settings.revenue');
});
