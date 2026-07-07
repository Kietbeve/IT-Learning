<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Modules\Document\Models\Document;
use Modules\Payment\Events\DocumentPurchased;
use Modules\Payment\Events\VipPurchased;
use Modules\Payment\Models\DocumentAccess;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\WalletTransaction;
use Modules\Payment\Services\OrderService;
use Modules\Payment\Services\PayOSService;
use Modules\Payment\Services\SubscriptionService;

class PaymentController extends Controller
{
    public function return(Request $request)
    {
        $orderCode = $request->query('order_code');
        $order = Order::where('order_code', $orderCode)->first();

        if (! $order) {
            return redirect()->route('payment.failed', ['reason' => 'Không tìm thấy đơn hàng.']);
        }

        if ($order->payment_status === 'paid') {
            if ($order->order_type === 'subscription') {
                return redirect()->route('user.subscription')->with('vip_success', [
                    'orderCode' => $order->order_code,
                    'amount' => $order->total_amount,
                    'packageKey' => $order->subscription_package_key,
                ]);
            }

            return view('payment::livewire.user.payment-success', [
                'orderCode' => $order->order_code,
                'documentTitle' => $order->items->first()->document_title_snapshot ?? 'Tài liệu',
                'amount' => $order->total_amount,
                'orderType' => $order->order_type,
                'packageKey' => $order->subscription_package_key,
            ]);
        }

        try {
            $payOS = app(PayOSService::class);
            $paymentInfo = $payOS->getPaymentInfo((int) $orderCode);

            $payOsStatus = $paymentInfo['status'] ?? null;

            if ($payOsStatus === 'PAID') {
                app(OrderService::class)->processSuccessfulPayment($orderCode, $paymentInfo);
                $order->refresh();

                if ($order->order_type === 'subscription') {
                    return redirect()->route('user.subscription')->with('vip_success', [
                        'orderCode' => $order->order_code,
                        'amount' => $order->total_amount,
                        'packageKey' => $order->subscription_package_key,
                    ]);
                }

                $title = $order->items->first()->document_title_snapshot ?? 'Tài liệu';

                return view('payment::livewire.user.payment-success', [
                    'orderCode' => $order->order_code,
                    'documentTitle' => $title,
                    'amount' => $order->total_amount,
                    'orderType' => $order->order_type,
                    'packageKey' => $order->subscription_package_key,
                ]);
            }

            if ($payOsStatus === 'CANCELLED') {
                $order->update(['order_status' => 'canceled', 'canceled_at' => now()]);

                return view('payment::livewire.user.payment-failed', [
                    'reason' => 'Giao dịch đã bị hủy.',
                    'documentId' => $order->items->first()->document_id ?? null,
                ]);
            }

            return view('payment::livewire.user.payment-failed', [
                'reason' => 'Giao dịch chưa được xác nhận. Vui lòng thử lại sau.',
                'documentId' => $order->items->first()->document_id ?? null,
            ]);

        } catch (\Exception $e) {
            return view('payment::livewire.user.payment-failed', [
                'reason' => 'Có lỗi xảy ra khi xác nhận thanh toán: '.$e->getMessage(),
                'documentId' => $order->items->first()->document_id ?? null,
            ]);
        }
    }

    public function cancel(Request $request)
    {
        $orderCode = $request->query('order_code');

        if ($orderCode) {
            $order = Order::where('order_code', $orderCode)->first();
            if ($order && $order->payment_status === 'pending') {
                $order->update([
                    'order_status' => 'canceled',
                    'canceled_at' => now(),
                ]);
            }
        }

        return view('payment::livewire.user.payment-failed', [
            'reason' => 'Bạn đã hủy giao dịch thanh toán.',
            'documentId' => $order ? ($order->items->first()->document_id ?? null) : null,
        ]);
    }

    public function failed(Request $request)
    {
        return view('payment::livewire.user.payment-failed', [
            'reason' => $request->query('reason', 'Có lỗi xảy ra trong quá trình thanh toán.'),
            'documentId' => $request->query('document_id'),
        ]);
    }
}
