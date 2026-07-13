<?php

use Illuminate\Support\Facades\Route;
use Modules\Payment\Http\Controllers\WebhookController;
use Modules\Payment\Http\Livewire\Admin\OrderManagement;
use Modules\Payment\Http\Livewire\Admin\PayoutReview;
use Modules\Payment\Http\Livewire\Admin\PlatformRevenue;
use Modules\Payment\Http\Livewire\Admin\RevenueSettings;
use Modules\Payment\Http\Livewire\Admin\SystemReports;
use Modules\Payment\Http\Livewire\Admin\TransactionList;
use Modules\Payment\Http\Livewire\Contributor\EarningsReport;
use Modules\Payment\Http\Livewire\Contributor\PayoutRequest;
use Modules\Payment\Http\Livewire\User\Purchases;
use Modules\Payment\Http\Livewire\User\Subscription;
use Modules\Payment\Http\Livewire\User\TransactionHistory;

Route::middleware(['auth'])->group(function () {
    Route::get('/user/purchases', Purchases::class)->name('user.purchases');
    Route::get('/user/transactions', TransactionHistory::class)->name('user.transactions');
    Route::get('/user/subscription', Subscription::class)->name('user.subscription');
});

Route::post('/webhook/payos', [WebhookController::class, 'handlePayOS'])->name('webhook.payos');

Route::middleware(['auth', 'role:contributor'])->prefix('contributor')->group(function () {
    Route::get('/transactions', Modules\Payment\Http\Livewire\Contributor\TransactionHistory::class)->name('contributor.transactions');
    Route::get('/payout-request', PayoutRequest::class)->name('contributor.payout-request');
    Route::get('/earnings', EarningsReport::class)->name('contributor.earnings');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/', PlatformRevenue::class)->name('admin.dashboard');
    Route::get('/orders', OrderManagement::class)->name('admin.orders.index');

    Route::get('/payouts/review', PayoutReview::class)->name('admin.payouts.review');
    Route::get('/reports', SystemReports::class)->name('admin.reports');
    Route::get('/settings/revenue', RevenueSettings::class)->name('admin.settings.revenue');
});
