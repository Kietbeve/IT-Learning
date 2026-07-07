<?php

namespace Modules\Payment\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Payment\Models\Order;
use Modules\Payment\Services\OrderService;
use Modules\Payment\Services\PayOSService;

trait WithPayOSPolling
{
    /**
     * Reusable method to poll PayOS API for payment status.
     * 
     * @param string|int|null $orderCode The order code to check
     * @param callable $onSuccess Closure to execute if payment is paid
     */
    protected function pollPayOSStatus($orderCode, callable $onSuccess): void
    {
        if (empty($orderCode)) {
            return;
        }

        $order = Order::where('order_code', (string) $orderCode)
            ->where('user_id', Auth::id())
            ->first();

        if (! $order) {
            return;
        }

        // Nếu DB đã có trạng thái paid (webhook đã xử lý), bỏ qua bước hỏi PayOS
        $isPaid = ($order->payment_status === 'paid');

        if (! $isPaid) {
            try {
                $payOS = app(PayOSService::class);
                $paymentInfo = $payOS->getPaymentInfo((int) $orderCode);

                if (isset($paymentInfo['status']) && $paymentInfo['status'] === 'PAID') {
                    // Uỷ thác toàn bộ xử lý cho OrderService (có lockForUpdate chống race condition)
                    $isPaid = app(OrderService::class)->processSuccessfulPayment($orderCode, $paymentInfo);
                }
            } catch (\Exception $e) {
                Log::error(class_basename($this) . ': PayOS API error', [
                    'order_code' => $orderCode,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($isPaid) {
            $onSuccess();
        }
    }
}
