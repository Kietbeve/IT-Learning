<?php

namespace Modules\Payment\Http\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Modules\Document\Models\Document;
use Modules\Payment\Jobs\ExpireOrderJob;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Services\OrderService;
use Modules\Payment\Services\PayOSService;
use WireUi\Traits\WireUiActions;

use Modules\Payment\Traits\WithPayOSPolling;

class CheckoutModal extends Component
{
    use WireUiActions;
    use WithPayOSPolling;

    public bool $showModal = false;

    public ?int $documentId = null;
    
    public ?string $guestEmail = null;

    public bool $loading = false;

    public array $document = [];

    public ?array $product = null;

    public array $paymentData = [];

    public int $remainingSeconds = 600;

    protected function getListeners()
    {
        return [
            'openCheckoutModal' => 'show',
            'trigger-payment-processing' => 'processPayment',
        ];
    }

    public function show(int $documentId, ?string $guestEmail = null): void
    {
        $this->documentId = $documentId;
        $this->guestEmail = $guestEmail;
        $this->loadDocument();
        $this->paymentData = [];

        // Reset timer default
        $this->remainingSeconds = 600;

        if (! empty($this->product)) {
            $this->processPayment();
        }

        // Dispatch timer with the final calculated seconds (600 or reused from pending order)
        $this->dispatch('reset-timer', seconds: $this->remainingSeconds);
        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->documentId = null;
        $this->guestEmail = null;
        $this->document = [];
        $this->product = null;
        $this->paymentData = [];
        $this->remainingSeconds = 600;
        $this->loading = false;
    }

    protected function loadDocument(): void
    {
        $doc = Document::with(['author', 'product', 'currentVersion', 'latestVersion'])->find($this->documentId);

        if (! $doc || ! $doc->product || ! $doc->product->is_active) {
            $this->notification()->error(
                title: 'Không thể thanh toán',
                description: 'Tài liệu này không có sản phẩm thanh toán hoặc đã bị vô hiệu hóa.'
            );
            $this->close();

            return;
        }

        $this->document = [
            'id' => $doc->id,
            'title' => $doc->title,
        ];

        $this->product = [
            'id' => $doc->product->id,
            'name' => $doc->product->name,
            'price' => (int) $doc->product->price,
            'sale_price' => $doc->product->sale_price ? (int) $doc->product->sale_price : null,
        ];
    }

    public function getFinalPriceProperty(): int
    {
        return $this->product['sale_price'] ?? $this->product['price'] ?? 0;
    }

    public function processPayment(): void
    {
        if (! $this->documentId || ! $this->product) {
            return;
        }

        $this->loading = true;

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $finalPrice = $this->finalPrice;
            $deviceId = request()->cookie('guest_device_id');

            // 1. Check existing pending order for this document
            $query = Order::where('order_type', 'document')
                ->where('payment_status', 'pending')
                ->where('expires_at', '>', now())
                ->where('total_amount', $finalPrice)
                ->whereHas('items', function ($q) {
                    $q->where('document_id', $this->documentId);
                });
                
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                $query->whereNull('user_id')->where('guest_email', $this->guestEmail);
            }

            $existingOrder = $query->first();

            if ($existingOrder && $existingOrder->checkout_data) {
                DB::rollBack();
                $this->paymentData = $existingOrder->checkout_data;
                $this->remainingSeconds = max(1, $existingOrder->expires_at->timestamp - time());
                $this->loading = false;

                return;
            }

            // 2. Cleanup expired old pending orders
            if ($user) {
                Order::expirePendingOrders($user->id);
            }

            $platformFeePercent = (int) \App\Services\SettingService::get('platform_fee_percent', 20);
            $contributorAmount = $finalPrice * (100 - $platformFeePercent) / 100;
            $platformAmount = $finalPrice * $platformFeePercent / 100;

            $productInfo = [
                'id' => $this->product['id'],
                'name' => $this->document['title'],
                'price' => $this->product['price'],
                'sale_price' => $this->product['sale_price'] ?? null
            ];

            $orderService = app(\Modules\Payment\Services\OrderService::class);
            $order = $orderService->createDocumentOrder(
                $user, 
                $this->documentId, 
                $productInfo, 
                $finalPrice, 
                $contributorAmount, 
                $platformAmount,
                $this->guestEmail,
                $deviceId
            );

            // Dispatch delayed job to expire order after 5 minutes
            ExpireOrderJob::dispatch($order->id)
                ->delay(now()->addMinutes(10));

            DB::commit();

            $payOS = app(PayOSService::class);

            $description = 'ITL ' . $order->order_code;
            
            $returnUrl = $user ? route('user.purchases') : route('documents.show', $this->documentId);
            $cancelUrl = $user ? route('user.purchases') : route('documents.show', $this->documentId);

            $paymentResponse = $payOS->createPaymentLink(
                orderCode: $order->order_code,
                amount: $finalPrice,
                description: $description,
                returnUrl: $returnUrl,
                cancelUrl: $cancelUrl,
                buyerName: $user ? $user->name : 'Khách',
                buyerEmail: $user ? $user->email : $this->guestEmail,
                expiredAt: now()->addMinutes(10)->timestamp,
            );

            if (isset($paymentResponse['checkoutUrl'])) {
                // Merge PayOS response with existing checkout_data to preserve device_id
                $mergedData = array_merge($order->checkout_data ?? [], $paymentResponse);
                $order->update(['checkout_data' => $mergedData]);
                $this->paymentData = $paymentResponse;
                $this->remainingSeconds = 600;
                $this->loading = false;
            } else {
                throw new \Exception('Không nhận được link thanh toán từ PayOS.');
            }

        } catch (\Exception $e) {
            DB::rollBack();

            $this->loading = false;
            $this->notification()->error(
                title: 'Lỗi thanh toán',
                description: $e->getMessage()
            );
        }
    }

    public function checkPaymentStatus()
    {
        if (empty($this->paymentData) || ! isset($this->paymentData['orderCode'])) {
            return;
        }

        $this->pollPayOSStatus($this->paymentData['orderCode'], function () {
            $this->dispatch('payment-completed');
            $this->dispatch('new-notification');
        });
    }

    public function render()
    {
        return view('payment::livewire.user.checkout-modal');
    }
}

