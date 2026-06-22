<?php

namespace Modules\Payment\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\DocumentAccess;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Models\WalletTransaction;
use Modules\Payment\Services\PayOSService;
use Modules\Payment\Services\SubscriptionService;
use Modules\Document\Models\Document;
use Modules\Auth\Models\User;

class PaymentController extends Controller
{
    public function return(Request $request)
    {
        $orderCode = $request->query('order_code');
        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return redirect()->route('payment.failed', ['reason' => 'Không tìm thấy đơn hàng.']);
        }

        if ($order->payment_status === 'paid') {
            if ($order->order_type === 'subscription') {
                return redirect()->route('student.subscription')->with('vip_success', [
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
                DB::beginTransaction();

                $order->update([
                    'payment_status' => 'paid',
                    'order_status' => 'completed',
                    'paid_at' => now(),
                ]);

                Payment::create([
                    'order_id' => $order->id,
                    'provider' => 'payos',
                    'transaction_code' => $paymentInfo['id'] ?? null,
                    'provider_order_code' => $paymentInfo['orderCode'] ?? null,
                    'amount' => $order->total_amount,
                    'status' => 'success',
                    'raw_response' => $paymentInfo,
                    'paid_at' => now(),
                ]);

                foreach ($order->items as $item) {
                    DocumentAccess::updateOrCreate(
                        [
                            'user_id' => $order->user_id,
                            'document_id' => $item->document_id,
                        ],
                        [
                            'order_item_id' => $item->id,
                            'access_type' => 'purchased',
                            'expires_at' => null,
                        ]
                    );
                }

                // Credit contributor for each document purchase
                foreach ($order->items as $item) {
                    if ($item->document_id && $item->contributor_amount > 0) {
                        $document = Document::find($item->document_id);
                        if ($document && $document->author_id) {
                            $author = User::find($document->author_id);
                            if ($author) {
                                $balanceBefore = $author->contributor_balance ?? 0;
                                $balanceAfter = $balanceBefore + $item->contributor_amount;
                                
                                $author->update(['contributor_balance' => $balanceAfter]);
                                
                                WalletTransaction::create([
                                    'user_id' => $author->id,
                                    'type' => 'earning',
                                    'amount' => $item->contributor_amount,
                                    'balance_before' => $balanceBefore,
                                    'balance_after' => $balanceAfter,
                                    'reference_type' => 'order_item',
                                    'reference_id' => $item->id,
                                    'note' => 'Doanh thu từ tài liệu: ' . $item->document_title_snapshot,
                                ]);
                            }
                        }
                    }
                }

                if ($order->order_type === 'subscription') {
                    $subscriptionService = app(SubscriptionService::class);
                    $subscriptionService->activateVip($order->user, $order->subscription_package_key);
                }

                DB::commit();

                if ($order->order_type === 'subscription') {
                    return redirect()->route('student.subscription')->with('vip_success', [
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
                'reason' => 'Có lỗi xảy ra khi xác nhận thanh toán: ' . $e->getMessage(),
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
