<?php

namespace Modules\Payment\Http\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Modules\Document\Models\Document;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Models\Product;
use Modules\Payment\Services\PayOSService;
use WireUi\Traits\WireUiActions;

class CheckoutModal extends Component
{
    use WireUiActions;

    public bool $showModal = false;
    public ?int $documentId = null;
    public bool $loading = false;
    public array $document = [];
    public ?array $product = null;

    protected function getListeners()
    {
        return [
            'openCheckoutModal' => 'show',
        ];
    }

    public function show(int $documentId): void
    {
        $this->documentId = $documentId;
        $this->loadDocument();
        $this->showModal = true;
    }

    public function close(): void
    {
        $this->showModal = false;
        $this->documentId = null;
        $this->document = [];
        $this->product = null;
        $this->loading = false;
    }

    protected function loadDocument(): void
    {
        $doc = Document::with(['author', 'product', 'currentVersion', 'latestVersion'])->find($this->documentId);

        if (!$doc || !$doc->product || !$doc->product->is_active) {
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
            'thumbnail' => $doc->thumbnail,
            'author_name' => $doc->author?->name ?? 'Không xác định',
            'category_name' => $doc->category?->name ?? 'Tài liệu',
            'file_type' => strtoupper($doc->file_type),
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
        if (!$this->documentId || !$this->product) {
            return;
        }

        $this->loading = true;

        try {
            DB::beginTransaction();

            $user = Auth::user();
            $finalPrice = $this->finalPrice;

            $orderCode = (int) (now()->timestamp . Str::random(4));

            $order = Order::create([
                'order_code' => (string) $orderCode,
                'user_id' => $user->id,
                'total_amount' => $finalPrice,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'download_token' => Str::random(64),
                'guest_download_limit' => 0,
                'guest_download_count' => 0,
            ]);

            $platformFeePercent = config('payment.platform_fee_percent', 10);
            $contributorAmount = $finalPrice * (100 - $platformFeePercent) / 100;
            $platformAmount = $finalPrice * $platformFeePercent / 100;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $this->product['id'],
                'document_id' => $this->documentId,
                'document_title_snapshot' => $this->document['title'],
                'unit_price' => $finalPrice,
                'quantity' => 1,
                'subtotal' => $finalPrice,
                'contributor_amount' => $contributorAmount,
                'platform_amount' => $platformAmount,
            ]);

            DB::commit();

            $payOS = app(PayOSService::class);

            $description = 'Mua: ' . Str::limit($this->document['title'], 50);

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

    public function render()
    {
        return view('payment::livewire.user.checkout-modal');
    }
}
