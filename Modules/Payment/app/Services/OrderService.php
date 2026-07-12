<?php

namespace Modules\Payment\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Document\Models\Document;
use Modules\Payment\Events\DocumentPurchased;
use Modules\Payment\Events\VipPurchased;
use Modules\Payment\Models\DocumentAccess;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Models\Payment;
use Modules\Payment\Models\WalletTransaction;
use Illuminate\Support\Str;
use Modules\Payment\Notifications\DocumentPurchaseSuccessNotification;

class OrderService
{
    public function __construct(
        protected SubscriptionService $subscriptionService
    ) {}

    public function createDocumentOrder(?User $user, int $documentId, array $productInfo, int $finalPrice, int $contributorAmount, int $platformAmount, ?string $guestEmail = null, ?string $deviceId = null): Order
    {
        return DB::transaction(function () use ($user, $documentId, $productInfo, $finalPrice, $contributorAmount, $platformAmount, $guestEmail, $deviceId) {
            $orderCode = (int) (now()->timestamp . Str::random(4));
            
            $checkoutData = [];
            if ($deviceId) {
                $checkoutData['device_id'] = $deviceId;
            }

            $order = Order::create([
                'order_code' => (string) $orderCode,
                'user_id' => $user ? $user->id : null,
                'guest_email' => $guestEmail,
                'guest_device_id' => $deviceId,
                'order_type' => 'document',
                'total_amount' => $finalPrice,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'download_token' => Str::random(64),
                'guest_download_limit' => $user ? 0 : 5,
                'guest_download_count' => 0,
                'expires_at' => now()->addMinutes(1),
                'checkout_data' => empty($checkoutData) ? null : $checkoutData,
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'document_id' => $documentId,
                'product_id' => $productInfo['id'],
                'document_title_snapshot' => $productInfo['name'],
                'unit_price' => $productInfo['sale_price'] ?? $productInfo['price'],
                'quantity' => 1,
                'subtotal' => $finalPrice,
                'contributor_amount' => $contributorAmount,
                'platform_amount' => $platformAmount,
            ]);

            return $order;
        });
    }

    public function createSubscriptionOrder(User $user, string $packageKey, array $packageInfo, int $finalPrice): Order
    {
        return DB::transaction(function () use ($user, $packageKey, $packageInfo, $finalPrice) {
            $orderCode = (int) (now()->timestamp . Str::random(4));
            $order = Order::create([
                'order_code' => (string) $orderCode,
                'user_id' => $user->id,
                'order_type' => 'subscription',
                'subscription_package_key' => $packageKey,
                'total_amount' => $finalPrice,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'expires_at' => now()->addMinutes(1),
            ]);

            return $order;
        });
    }

    public function createVipDownloadOrder(User $user, Document $doc): OrderItem
    {
        return DB::transaction(function () use ($user, $doc) {
            $orderCode = now()->timestamp . rand(1000, 9999);
            $order = Order::create([
                'order_code' => (string) $orderCode,
                'order_type' => 'document',
                'user_id' => $user->id,
                'total_amount' => 0,
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'paid_at' => now(),
                'download_token' => Str::random(64),
            ]);

            // Calculate revenue split for VIP download based on VIP sale price and quota
            $vipPrice = (int) \App\Services\SettingService::get('vip_sale_price', 100000);
            $vipQuota = (int) \App\Services\SettingService::get('vip_quota', 5);
            $valuePerDownload = $vipQuota > 0 ? (int) floor($vipPrice / $vipQuota) : 0;
            
            $platformFeePercent = (int) \App\Services\SettingService::get('platform_fee_percent', 20);
            $platformAmount = (int) floor($valuePerDownload * $platformFeePercent / 100);
            $contributorAmount = $valuePerDownload - $platformAmount;

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $doc->product->id,
                'document_id' => $doc->id,
                'document_title_snapshot' => $doc->title,
                'unit_price' => 0,
                'quantity' => 1,
                'subtotal' => 0,
                'contributor_amount' => $contributorAmount,
                'platform_amount' => $platformAmount,
            ]);

            $this->addContributorCommission($orderItem);

            return $orderItem;
        });
    }

    /**
     * Xử lý đơn hàng đã thanh toán thành công.
     * Sử dụng lockForUpdate() để chống race condition khi cả Webhook và Polling
     * cùng nhận được tín hiệu thanh toán thành công cùng một lúc.
     *
     * @return bool true nếu đơn được xử lý lần đầu, false nếu đã xử lý rồi
     */
    public function processSuccessfulPayment(int|string $orderCode, array $paymentInfo): bool
    {
        return DB::transaction(function () use ($orderCode, $paymentInfo) {
            // Lock dòng dữ liệu này lại. Nếu tiến trình khác (Webhook hoặc Polling)
            // đang xử lý đơn này, tiến trình hiện tại sẽ phải đợi đến khi lock được giải phóng.
            $order = Order::where('order_code', (string) $orderCode)
                ->lockForUpdate()
                ->first();

            if (! $order) {
                Log::warning('OrderService: Order not found', ['order_code' => $orderCode]);

                return false;
            }

            // Sau khi lấy được lock, kiểm tra lại trạng thái.
            // Nếu tiến trình kia đã xử lý xong và commit 'paid', chúng ta bỏ qua.
            if ($order->payment_status === 'paid') {
                Log::info('OrderService: Order already processed, skipping.', ['order_code' => $orderCode]);

                return false;
            }

            $amount = $paymentInfo['amount'] ?? $order->total_amount;
            $transactionCode = $paymentInfo['transactionDateTime'] ?? null;

            // 1. Cập nhật trạng thái đơn hàng
            $order->update([
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'paid_at' => now(),
                'completed_at' => now(),
            ]);

            // 2. Tạo bản ghi thanh toán
            Payment::create([
                'order_id' => $order->id,
                'provider' => 'payos',
                'transaction_code' => $transactionCode ?? 'PROCESSED_'.$orderCode,
                'provider_order_code' => (string) $orderCode,
                'amount' => $amount,
                'status' => 'success',
                'raw_response' => $paymentInfo,
                'paid_at' => now(),
            ]);

            // 3. Xử lý theo loại đơn hàng
            if ($order->order_type === 'subscription' && $order->subscription_package_key) {
                $this->handleSubscriptionOrder($order);
            }

            if ($order->order_type === 'document') {
                $this->handleDocumentOrder($order);
            }

            Log::info('OrderService: Payment processed successfully.', [
                'order_code' => $orderCode,
                'type' => $order->order_type,
                'user_id' => $order->user_id,
            ]);

            return true;
        });
    }

    /**
     * Kích hoạt gói VIP và bắn sự kiện sau khi transaction commit.
     */
    protected function handleSubscriptionOrder(Order $order): void
    {
        $this->subscriptionService->activateVip($order->user, $order->subscription_package_key);

        // Bắn event SAU khi DB commit để tránh rollback nếu event bị lỗi
        DB::afterCommit(function () use ($order) {
            try {
                event(new VipPurchased($order->user, $order, $order->subscription_package_key));
            } catch (\Exception $e) {
                Log::error('OrderService: VipPurchased event failed', [
                    'order_code' => $order->order_code,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    /**
     * Cấp quyền truy cập tài liệu, cộng tiền hoa hồng cho tác giả, gửi thông báo.
     */
    protected function handleDocumentOrder(Order $order): void
    {
        foreach ($order->items as $item) {
            // Cấp quyền truy cập tài liệu (Chỉ đối với người dùng đã đăng nhập)
            if ($order->user_id) {
                DocumentAccess::updateOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'document_id' => $item->document_id,
                    ],
                    [
                        'order_item_id' => $item->id,
                        'access_type' => 'purchased',
                    ]
                );
            }

            // Cộng tiền hoa hồng cho tác giả
            $this->addContributorCommission($item);
        }

        // Bắn event và gửi notification SAU khi DB commit
        DB::afterCommit(function () use ($order) {
            try {
                $firstItem = $order->items->first();
                $doc = $firstItem ? Document::find($firstItem->document_id) : null;
                if (!$doc) {
                    Log::warning('OrderService: Document not found for first item', ['order_code' => $order->order_code]);
                }
                
                if ($order->user_id) {
                    event(new DocumentPurchased($order->user, $order));
                } elseif ($order->guest_email) {
                    \Illuminate\Support\Facades\Mail::to($order->guest_email)
                        ->send(new \Modules\Payment\Mail\GuestPurchaseReceiptMail($order));
                }

            } catch (\Exception $e) {
                Log::error('OrderService: DocumentPurchased event or Guest receipt failed', [
                    'order_code' => $order->order_code,
                    'error' => $e->getMessage(),
                ]);
            }
        });
    }

    /**
     * Cộng tiền hoa hồng cho tác giả từ một mục đơn hàng (thanh toán tiền hoặc tải VIP).
     */
    protected function addContributorCommission(OrderItem $item): void
    {
        if ($item->document_id && $item->contributor_amount > 0) {
            $document = Document::find($item->document_id);
            if ($document && $document->author_id) {
                $author = User::find($document->author_id);
                if ($author) {
                    $balanceBefore = $author->contributor_balance ?? 0;

                    $author->increment('contributor_balance', $item->contributor_amount);
                    $author->refresh();

                    WalletTransaction::create([
                        'user_id' => $author->id,
                        'type' => 'earning',
                        'amount' => $item->contributor_amount,
                        'balance_before' => $balanceBefore,
                        'balance_after' => $author->contributor_balance,
                        'reference_type' => 'order_item',
                        'reference_id' => $item->id,
                        'note' => 'Doanh thu từ tài liệu: '.$item->document_title_snapshot,
                    ]);
                }
            }
        }
    }
}
