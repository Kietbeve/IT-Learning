<?php

namespace Modules\Payment\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Payment\Services\PayOSService;
use Modules\Payment\Services\SubscriptionService;
use WireUi\Traits\WireUiActions;

class Subscription extends Component
{
    use WireUiActions;

    public $packages = [];
    public $vipStatus = [];
    public $loading = false;
    public $showSuccessModal = false;
    public $successData = [];
    public $errorMessage = '';

    public function mount()
    {
        $subscriptionService = app(SubscriptionService::class);
        
        $this->packages = $subscriptionService->getPackages();
        $this->vipStatus = $subscriptionService->getVipStatus(Auth::user());

        if (session()->has('vip_success')) {
            $this->successData = session('vip_success');
            $this->showSuccessModal = true;
            session()->forget('vip_success');
        }
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        $this->successData = [];
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

            if (!$package) {
                throw new \Exception('Gói VIP không hợp lệ.');
            }

            $user = Auth::user();
            $finalPrice = $subscriptionService->getFinalPrice($packageKey);

            if (!$finalPrice || $finalPrice <= 0) {
                throw new \Exception('Số tiền gói VIP không hợp lệ.');
            }

            $orderCode = (int) (now()->timestamp . rand(1000, 9999));

            DB::beginTransaction();

            $order = Order::create([
                'order_code' => (string) $orderCode,
                'order_type' => 'subscription',
                'subscription_package_key' => $packageKey,
                'user_id' => $user->id,
                'total_amount' => $finalPrice,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'download_token' => Str::random(64),
                'guest_download_limit' => 0,
                'guest_download_count' => 0,
            ]);

            $payOSClientId = config('payment.payos.client_id');

            if (!$payOSClientId) {
                // Test mode: activate VIP directly without PayOS
                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'completed',
                    'paid_at' => now(),
                ]);

                $subscriptionService->activateVip($user, $packageKey);

                Payment::create([
                    'order_id' => $order->id,
                    'provider' => 'payos',
                    'transaction_code' => 'TEST_' . $orderCode,
                    'provider_order_code' => (string) $orderCode,
                    'amount' => $finalPrice,
                    'status' => 'success',
                    'raw_response' => ['mode' => 'test'],
                    'paid_at' => now(),
                ]);

                DB::commit();

                session()->flash('vip_success', [
                    'orderCode' => $order->order_code,
                    'amount' => $finalPrice,
                    'packageKey' => $packageKey,
                ]);

                $this->redirect(route('student.subscription'));
                return;
            }

            DB::commit();

            $payOS = app(PayOSService::class);

            $description = 'Mua gói VIP: ' . $package['name'];

            $paymentResponse = $payOS->createPaymentLink(
                orderCode: $orderCode,
                amount: $finalPrice,
                description: $description,
                returnUrl: route('payment.return', ['order_code' => $orderCode]),
                cancelUrl: route('payment.cancel', ['order_code' => $orderCode]),
                buyerName: $user->name,
                buyerEmail: $user->email,
            );

            if (isset($paymentResponse['checkoutUrl'])) {
                $this->redirect($paymentResponse['checkoutUrl']);
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
