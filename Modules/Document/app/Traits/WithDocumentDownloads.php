<?php

namespace Modules\Document\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentDownload;
use Modules\Payment\Models\DocumentAccess;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Services\OrderService;
use Modules\Payment\Services\SubscriptionService;
use Livewire\Attributes\On;

trait WithDocumentDownloads
{
    public $hasDownloaded = false;
    public $showVipConfirmModal = false;
    
    // Guest Purchase properties
    public bool $showGuestEmailModal = false;
    public string $guestEmail = '';

    public function promptVipDownload()
    {
        if (!Auth::check()) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Vui lòng đăng nhập để sử dụng tính năng này.']);
            return;
        }

        $subscriptionService = app(SubscriptionService::class);
        $user = Auth::user();
        $isVipActive = $subscriptionService->isVipActive($user);
        $hasQuota = $user->vip_download_quota > 0;

        if ($isVipActive && $hasQuota) {
            $this->showVipConfirmModal = true;
        } elseif ($isVipActive && !$hasQuota) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Hết lượt tải VIP. Vui lòng nạp thêm lượt hoặc mua trực tiếp tài liệu.']);
        } else {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Gói VIP đã hết hạn. Vui lòng gia hạn để tiếp tục tải xuống bằng VIP.']);
        }
    }

    public function executeVipDownload()
    {
        if (!Auth::check()) return;

        $userId = Auth::id();
        $doc = Document::with('product')->find($this->documentId);
        
        if (!$doc || !$doc->product) return;

        $subscriptionService = app(SubscriptionService::class);
        $user = Auth::user();
        $isVipActive = $subscriptionService->isVipActive($user);
        $hasQuota = $user->vip_download_quota > 0;

        if ($isVipActive && $hasQuota) {
            try {
                DB::beginTransaction();
                
                $orderService = app(OrderService::class);
                $orderItem = $orderService->createVipDownloadOrder($user, $doc);
                
                DocumentAccess::create([
                    'user_id' => $userId,
                    'document_id' => $doc->id,
                    'order_item_id' => $orderItem->id,
                    'access_type' => 'vip',
                ]);
                
                $subscriptionService->decreaseQuota(Auth::user());
                
                DB::commit();
                
                // Gửi thông báo cho người mua thông qua Event
                event(new \Modules\Payment\Events\DocumentDownloadedByVip(Auth::user(), $doc));

                $this->dispatch('vip-download-success');

                $this->hasDownloaded = true; // Trigger re-render to show download button
            } catch (\Exception $e) {
                DB::rollBack();
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
            }
        } elseif ($isVipActive && !$hasQuota) {
            $this->dispatch('notify', ['type' => 'warning', 'message' => 'Hết lượt tải VIP. Vui lòng nạp thêm lượt hoặc mua trực tiếp tài liệu.']);
        } else {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Gói VIP đã hết hạn. Vui lòng gia hạn để tiếp tục tải xuống bằng VIP.']);
        }
    }

    #[On('payment-completed')]
    public function refreshAfterPayment()
    {
        // Force Livewire re-render để query lại hasAccess từ DB
        // UI sẽ hiện nút "Tải xuống" thay vì nút "Mua"
        $this->hasDownloaded = true;
        
        // Cần gọi lại hàm mount để reload trạng thái nếu cần
        $this->mount($this->documentId);

        // Trigger full component re-render
        $this->dispatch('$refresh');
    }

    public function download()
    {
        $doc = Document::with(['currentVersion', 'latestVersion', 'product'])->find($this->documentId);
        if (!$doc) return;

        // Check file physical existence on storage
        $filePath = $doc->currentVersion?->file_original_path;
        if (!$filePath || !$this->checkFileExists($filePath)) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Rất tiếc, tệp tin gốc của tài nguyên này không còn tồn tại trên hệ thống lưu trữ.']);
            return;
        }

        $accessInfo = $this->checkDocumentAccess($doc);

        if (!$accessInfo['hasAccess']) {
            if (!Auth::check()) {
                return redirect()->route('login');
            } else {
                $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn cần mua tài liệu này trước khi tải xuống.']);
                return;
            }
        }

        if ($accessInfo['guestOrder']) {
            $guestOrder = $accessInfo['guestOrder'];
            // Check guest download limit
            if ($guestOrder->guest_download_count >= $guestOrder->guest_download_limit) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Bạn đã hết 5 lượt tải. Vui lòng đăng nhập bằng email lúc mua để sở hữu tài liệu vĩnh viễn.']);
                return;
            }
            // Increment guest download count
            $guestOrder->increment('guest_download_count');
        }

        // Authenticated user visibility check (if any)
        if (Auth::check() && $doc->visibility === 'private' && $doc->author_id !== Auth::id()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Tài liệu này hiện đang ở chế độ riêng tư.']);
            return;
        }

        DocumentDownload::create([
            'document_id' => $doc->id,
            'user_id' => Auth::id(),
            'order_item_id' => $accessInfo['orderItemId'],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'source' => (bool)($doc->product && $doc->product->is_active) ? 'paid' : 'free',
            'downloaded_at' => now(),
        ]);
        
        $token = \Illuminate\Support\Str::random(40);

        \Illuminate\Support\Facades\Cache::put("doc_download_{$token}", [
            'document_id' => $doc->id,
            'user_id' => Auth::id(),
            'order_item_id' => $accessInfo['orderItemId'],
            'ip' => request()->ip()
        ], now()->addMinutes(30));

        $downloadUrl = route('documents.download', ['token' => $token]);
        $this->dispatch('start-download', url: $downloadUrl);
        
        $this->hasDownloaded = true;
    }

    public function buyDocument()
    {
        $doc = Document::find($this->documentId);
        if (!$doc || !$doc->product || !$doc->product->is_active) return;

        // Check visibility
        if ($doc->visibility === 'private' && $doc->author_id !== Auth::id()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Tài liệu này hiện đang ở chế độ riêng tư.']);
            return;
        }

        if (!Auth::check()) {
            $this->showGuestEmailModal = true;
            return;
        }

        $this->dispatch('openCheckoutModal', documentId: $this->documentId);
    }

    public function continueGuestPurchase()
    {
        $this->validate(['guestEmail' => 'required|email|max:255']);
        $this->showGuestEmailModal = false;
        $this->dispatch('openCheckoutModal', documentId: $this->documentId, guestEmail: $this->guestEmail);
    }
}
