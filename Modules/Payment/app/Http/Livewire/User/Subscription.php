<?php

namespace Modules\Payment\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Modules\Payment\Jobs\ExpireOrderJob;
use Modules\Payment\Models\Order;
use Modules\Payment\Services\OrderService;
use Modules\Payment\Services\PayOSService;
use Modules\Payment\Services\SubscriptionService;
use Modules\Payment\Traits\WithPayOSPolling;
use WireUi\Traits\WireUiActions;

class Subscription extends Component
{
    use WireUiActions;
    use WithPayOSPolling;

    public $packages = [];

    public $vipStatus = [];

    public $loading = false;

    public $errorMessage = '';

    public $showPaymentModal = false;

    public $paymentData = [];

    public $currentOrderCode = null;

    public $remainingSeconds = 600;

    public function mount()
    {
        $subscriptionService = app(SubscriptionService::class);

        $this->packages = $subscriptionService->getPackages();
        $this->vipStatus = $subscriptionService->getVipStatus(Auth::user());

    }

    public function checkPaymentStatus()
    {
        $this->pollPayOSStatus($this->currentOrderCode, function () {
            $this->closePaymentModal();
            $subscriptionService = app(SubscriptionService::class);
            $this->vipStatus = $subscriptionService->getVipStatus(Auth::user());

            $this->dispatch('payment-success');
            $this->dispatch('new-notification');

            Log::info('checkPaymentStatus: Payment completed', ['order_code' => $this->currentOrderCode]);
        });
    }

    public function closePaymentModal()
    {
        $this->showPaymentModal = false;
        $this->currentOrderCode = null;
        $this->paymentData = [];
        $this->remainingSeconds = 600;
    }

    public function refreshVipStatus()
    {
        if (! Auth::check()) {
            return;
        }

        $subscriptionService = app(SubscriptionService::class);
        $this->vipStatus = $subscriptionService->getVipStatus(Auth::user());
    }

    public function getIsVipActiveProperty()
    {
        return $this->vipStatus['is_active'] ?? false;
    }

    public function getVipExpiresAtProperty()
    {
        return $this->vipStatus['expires_at'];
    }

    public function getVipQuotaProperty()
    {
        return $this->vipStatus['quota_remaining'] ?? 0;
    }

    public function getDaysRemainingProperty()
    {
        return $this->vipStatus['days_remaining'] ?? 0;
    }

    public function purchaseVip($packageKey)
    {
        $this->loading = true;
        $this->errorMessage = '';

        try {
            $subscriptionService = app(SubscriptionService::class);
            $package = $subscriptionService->getPackage($packageKey);

            if (! $package) {
                throw new \Exception('Gói VIP không hợp lệ.');
            }

            $user = Auth::user();
            $finalPrice = $subscriptionService->getFinalPrice($packageKey);

            if (! $finalPrice || $finalPrice <= 0) {
                throw new \Exception('Số tiền gói VIP không hợp lệ.');
            }

            // Database là Source of Truth - Query 1 lần duy nhất
            $validOrder = Order::where('user_id', $user->id)
                ->where('subscription_package_key', $packageKey)
                ->where('payment_status', 'pending')
                ->where('order_status', 'pending')
                ->where('expires_at', '>', now())
                ->first();

            if ($validOrder) {
                // Tìm checkout data: DB trước, Cache sau
                $checkoutData = $validOrder->checkout_data
                    ?? Cache::get('payos_data_'.$validOrder->order_code);

                if ($checkoutData) {
                    $this->paymentData = $checkoutData;
                    $this->currentOrderCode = (string) $validOrder->order_code;
                    $this->remainingSeconds = max(1, $validOrder->expires_at->timestamp - time());
                    $this->showPaymentModal = true;
                    $this->loading = false;

                    return;
                }

                // Không có checkout data → hủy đơn cũ, tạo mới
                $validOrder->update([
                    'payment_status' => 'expired',
                    'order_status' => 'expired',
                    'canceled_at' => now(),
                ]);
            }

            // Không có đơn cũ hợp lệ hoặc đơn cũ bị lỗi → Reset state và tạo mới
            $this->currentOrderCode = null;
            $this->paymentData = [];

            // Step 2: Lazy cleanup expired pending orders of this user
            Order::expirePendingOrders($user->id);

            DB::beginTransaction();

            $orderService = app(\Modules\Payment\Services\OrderService::class);
            $order = $orderService->createSubscriptionOrder(
                $user, 
                $packageKey, 
                $package, 
                $finalPrice
            );

            // Dispatch delayed job to expire order after 5 minutes
            ExpireOrderJob::dispatch($order->id)->delay(now()->addMinutes(10));

            $payOSClientId = config('payment.payos.client_id');

            if (! $payOSClientId) {
                // Test mode: delegate to OrderService
                DB::commit();

                app(\Modules\Payment\Services\OrderService::class)->processSuccessfulPayment($order->order_code, [
                    'amount' => $finalPrice,
                    'transactionDateTime' => 'TEST_' . $order->order_code,
                    'mode' => 'test'
                ]);

                session()->flash('vip_success', [
                    'orderCode' => $order->order_code,
                    'amount' => $finalPrice,
                    'packageKey' => $packageKey,
                ]);

                $this->redirect(route('user.subscription'));

                return;
            }

            DB::commit();

            $payOS = app(PayOSService::class);

            $description = 'ITL ' . $order->order_code;

            $paymentResponse = $payOS->createPaymentLink(
                orderCode: $order->order_code,
                amount: $finalPrice,
                description: $description,
                returnUrl: route('user.subscription'),
                cancelUrl: route('user.subscription'),
                buyerName: $user->name,
                buyerEmail: $user->email,
                expiredAt: now()->addMinutes(10)->timestamp,
            );

            if (isset($paymentResponse['checkoutUrl'])) {
                Cache::put('payos_data_'.$order->order_code, $paymentResponse, now()->addMinutes(10));

                // Lưu vào database để dùng lâu dài (không phụ thuộc cache)
                $order->update(['checkout_data' => $paymentResponse]);

                $this->paymentData = $paymentResponse;
                $this->currentOrderCode = (string) $order->order_code;
                $this->remainingSeconds = 600;
                $this->showPaymentModal = true;
                $this->loading = false;

                return;
            }

            throw new \Exception('Không nhận được link thanh toán từ PayOS.');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->loading = false;
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('payment::livewire.user.subscription')->layout('layouts.user');
    }
}
