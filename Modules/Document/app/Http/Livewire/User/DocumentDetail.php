<?php

namespace Modules\Document\Http\Livewire\User;

use Livewire\Component;
use Livewire\Attributes\On;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentFavorite;
use Modules\Document\Models\DocumentReview;
use Modules\Document\Models\DocumentDownload;
use Modules\Document\Models\DocumentComment;
use Modules\Payment\Models\DocumentAccess;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Services\SubscriptionService;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentDetail extends Component
{
    public $documentId;
    public $rating = 5;
    public $reviewContent = '';
    public $hasReviewed = false;
    public $hasDownloaded = false;
    protected $zipFiles = [];
    public $showReportModal = false;
    public $reportReason = 'Bản quyền';
    public $reportDetails = '';
    public $editReviewId = null;
    public $editReviewContent = '';
    public $editRating = 5;
    public $newComment = '';
    public $replyTo = null;
    public $replyContent = '';
    public $editingCommentId = null;
    public $editingContent = '';

    public function mount($id, $slug = null)
    {
        $this->documentId = $id;

        $doc = Document::with(['currentVersion', 'latestVersion', 'author'])->find($id);
        if (!$doc) {
            abort(404);
        }

        // Security checks: prevent URL probing of hidden documents
        // Only show approved documents to regular users
        if ($doc->status !== 'approved') {
            abort(404);
        }

        // 3. Check visibility - use 404 to not reveal document existence
        $userId = Auth::id();
        if ($doc->visibility === 'private' && (!$userId || $doc->author_id !== $userId)) {
            abort(404);
        }

        // Increment view count (without updating updated_at timestamp)
        \Illuminate\Support\Facades\DB::table('documents')
            ->where('id', $doc->id)
            ->increment('view_count');

    }

    public function toggleFavorite()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $doc = Document::find($this->documentId);
        if (!$doc) return;

        // Check visibility
        if ($doc->visibility === 'private' && $doc->author_id !== $userId) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Tài liệu này hiện đang ở chế độ riêng tư.']);
            return;
        }

        $fav = DocumentFavorite::where('document_id', $this->documentId)->where('user_id', $userId)->first();

        if ($fav) {
            $fav->delete();
            \Illuminate\Support\Facades\DB::table('documents')
                ->where('id', $doc->id)
                ->decrement('favorite_count');
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã bỏ lưu tài liệu']);
        } else {
            DocumentFavorite::create([
                'document_id' => $this->documentId,
                'user_id' => $userId
            ]);
            \Illuminate\Support\Facades\DB::table('documents')
                ->where('id', $doc->id)
                ->increment('favorite_count');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã lưu tài liệu vào danh sách yêu thích']);
        }
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

        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check visibility
        $userId = Auth::id();
        if ($doc->visibility === 'private' && $doc->author_id !== $userId) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Tài liệu này hiện đang ở chế độ riêng tư.']);
            return;
        }

        // Check is_downloadable flag
        if (!$doc->is_downloadable) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Tài liệu này chỉ hỗ trợ xem online, không cho phép tải xuống.']);
            return;
        }

        $isPaid = (bool)($doc->product && $doc->product->is_active);
        $hasAccess = false;
        $orderItemId = null;

        if ($isPaid) {
            $access = DocumentAccess::where('user_id', $userId)
                ->where('document_id', $doc->id)
                ->first();

        if ($access) {
            $orderItemId = $access->order_item_id;
            
            // Check loại access: 'paid' (tiền thật) hay 'vip' (quota)
            if ($access->access_type === 'paid') {
                // MUA BẰNG TIỀN → Quyền vĩnh viễn, không cần check VIP
                $hasAccess = true;
            } else {
                // MUA BẰNG VIP QUOTA → PAY-PER-DOWNLOAD: Trừ quota MỖI LẦN tải
                $user = Auth::user();
                $isVipActive = $user->vip_expires_at && $user->vip_expires_at->isFuture();
                $hasQuota = $user->vip_download_quota > 0;
                
                if ($isVipActive && $hasQuota) {
                    // VIP active + còn quota → Trừ quota rồi cho tải
                    try {
                        DB::beginTransaction();
                        $subscriptionService = app(SubscriptionService::class);
                        $subscriptionService->decreaseQuota(Auth::user());
                        DB::commit();
                        $hasAccess = true;
                    } catch (\Exception $e) {
                        DB::rollBack();
                        $this->dispatch('notify', ['type' => 'error', 'message' => 'Lỗi tải xuống: ' . $e->getMessage()]);
                        return;
                    }
                } else if ($isVipActive && !$hasQuota) {
                    // VIP còn hạn NHƯNG hết quota
                    $this->dispatch('notify', ['type' => 'info', 'message' => 'Hết lượt tải VIP. Vui lòng nạp thêm lượt hoặc mua tài liệu.']);
                    return;
                } else {
                    // VIP hết hạn
                    $this->dispatch('notify', ['type' => 'info', 'message' => 'Gói VIP đã hết hạn. Vui lòng gia hạn VIP hoặc mua tài liệu để tải xuống.']);
                    return;
                }
            }
        } else {
            $subscriptionService = app(SubscriptionService::class);
            if ($subscriptionService->canDownloadPremium(Auth::user())) {
                try {
                    DB::beginTransaction();
                    
                    $orderCode = now()->timestamp . rand(1000, 9999);
                    $order = Order::create([
                        'order_code' => (string) $orderCode,
                        'order_type' => 'document',
                        'user_id' => $userId,
                        'total_amount' => 0,
                        'payment_status' => 'paid',
                        'order_status' => 'completed',
                        'paid_at' => now(),
                        'download_token' => \Illuminate\Support\Str::random(64),
                    ]);
                    
                    $orderItem = OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $doc->product->id,
                        'document_id' => $doc->id,
                        'document_title_snapshot' => $doc->title,
                        'unit_price' => 0,
                        'quantity' => 1,
                        'subtotal' => 0,
                        'contributor_amount' => 0,
                        'platform_amount' => 0,
                    ]);
                    
                    DocumentAccess::create([
                        'user_id' => $userId,
                        'document_id' => $doc->id,
                        'order_item_id' => $orderItem->id,
                        'access_type' => 'vip',
                    ]);
                    
                    $subscriptionService->decreaseQuota(Auth::user());
                    
                    DB::commit();
                    
                    $hasAccess = true;
                    $orderItemId = $orderItem->id;
                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->dispatch('notify', ['type' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()]);
                    return;
                }
            } else {
                $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn cần mua tài liệu này trước khi tải xuống.']);
                return;
            }
        }
        } else {
            // Free document
            $hasAccess = true;
        }

        if ($hasAccess) {
            DocumentDownload::create([
                'document_id' => $doc->id,
                'user_id' => $userId,
                'order_item_id' => $orderItemId,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'source' => $isPaid ? 'paid' : 'free',
                'downloaded_at' => now(),
            ]);
            
            $token = \Illuminate\Support\Str::random(40);

            \Illuminate\Support\Facades\Cache::put("doc_download_{$token}", [
                'document_id' => $doc->id,
                'user_id' => $userId,
                'order_item_id' => $orderItemId,
                'ip' => request()->ip()
            ], now()->addMinutes(30));

            $downloadUrl = route('documents.download', ['token' => $token]);
            $this->dispatch('start-download', url: $downloadUrl);
            
            $this->hasDownloaded = true;
        }
    }

    #[On('payment-completed')]
    public function refreshAfterPayment()
    {
        // Force Livewire re-render để query lại hasAccess từ DB
        // Sau khi mua, DocumentAccess đã tồn tại → hasAccess = true
        // → UI sẽ hiện nút "Tải xuống" thay vì nút "Mua"
        $this->hasDownloaded = true;
        
        // Trigger full component re-render
        $this->dispatch('$refresh');
    }

    public function submitReview()
    {
        if (!Auth::check()) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Vui lòng đăng nhập để đánh giá.']);
            return;
        }

        $userId = Auth::id();

        // Lấy DocumentDownload gần nhất của user cho document này
        $latestDownload = DocumentDownload::where('document_id', $this->documentId)
            ->where('user_id', $userId)
            ->latest('downloaded_at')
            ->first();

        if (!$latestDownload) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn cần tải tài liệu trước khi có thể đánh giá.']);
            return;
        }

        // FREE DOC: Chỉ cho phép 1 review duy nhất
        $doc = Document::find($this->documentId);
        $isFree = !$doc || !$doc->product || !$doc->product->is_active;
        
        if ($isFree) {
            $anyReview = DocumentReview::where('document_id', $this->documentId)
                ->where('user_id', $userId)
                ->exists();
            if ($anyReview) {
                $this->dispatch('notify', ['type' => 'info', 'message' => 'Tài liệu miễn phí chỉ được đánh giá 1 lần.']);
                return;
            }
        }

        // Check xem download này đã được review chưa
        $hasReviewedThisDownload = DocumentReview::where('document_download_id', $latestDownload->id)
            ->exists();

        if ($hasReviewedThisDownload) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn đã đánh giá lần tải này rồi. Vui lòng tải lại để đánh giá thêm.']);
            return;
        }

        $this->validate([
            'rating' => 'required|integer|between:1,5',
            'reviewContent' => 'required|string|min:20|max:500',
        ], [
            'reviewContent.min' => 'Nhận xét phải có độ dài tối thiểu 20 ký tự.',
            'reviewContent.max' => 'Nhận xét có độ dài tối đa là 500 ký tự.',
        ]);

        DocumentReview::create([
            'document_id' => $this->documentId,
            'user_id' => $userId,
            'document_download_id' => $latestDownload->id,
            'rating' => $this->rating,
            'review' => $this->reviewContent,
            'status' => 'visible',
        ]);

        $this->reviewContent = '';
        $this->rating = 5;
        $this->hasReviewed = true;

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Gửi đánh giá thành công!']);
    }

    public function startEdit($reviewId)
    {
        if (!Auth::check()) return;

        $review = DocumentReview::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$review) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Không tìm thấy đánh giá.']);
            return;
        }

        if ($review->created_at->diffInHours(now()) >= 24) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã quá 24 giờ, bạn không thể chỉnh sửa đánh giá này.']);
            return;
        }

        $this->editReviewId = $review->id;
        $this->editReviewContent = $review->review;
        $this->editRating = $review->rating;
    }

    public function cancelEdit()
    {
        $this->editReviewId = null;
        $this->editReviewContent = '';
        $this->editRating = 5;
    }

    public function updateReview()
    {
        if (!Auth::check()) return;

        $review = DocumentReview::where('id', $this->editReviewId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$review) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Không tìm thấy đánh giá.']);
            $this->cancelEdit();
            return;
        }

        if ($review->created_at->diffInHours(now()) >= 24) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã quá 24 giờ, bạn không thể chỉnh sửa đánh giá này.']);
            $this->cancelEdit();
            return;
        }

        $this->validate([
            'editRating' => 'required|integer|between:1,5',
            'editReviewContent' => 'required|string|min:20|max:500',
        ], [
            'editReviewContent.min' => 'Nhận xét phải có độ dài tối thiểu 20 ký tự.',
            'editReviewContent.max' => 'Nhận xét có độ dài tối đa là 500 ký tự.',
        ]);

        $review->update([
            'rating' => $this->editRating,
            'review' => $this->editReviewContent,
        ]);

        $this->cancelEdit();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Cập nhật đánh giá thành công!']);
    }

    public function addComment()
    {
        if (!Auth::check()) return;

        $this->validate([
            'newComment' => 'required|string|min:1|max:1000',
        ]);

        DocumentComment::create([
            'document_id' => $this->documentId,
            'user_id' => Auth::id(),
            'content' => $this->newComment,
            'status' => 'visible',
        ]);

        $this->newComment = '';

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã đăng bình luận.']);
    }

    public function startReply($commentId)
    {
        if (!Auth::check()) return;

        $comment = DocumentComment::find($commentId);
        if (!$comment || $comment->document_id != $this->documentId) return;

        $this->replyTo = $commentId;
        $this->replyContent = '';
    }

    public function cancelReply()
    {
        $this->replyTo = null;
        $this->replyContent = '';
    }

    public function addReply()
    {
        if (!Auth::check() || !$this->replyTo) return;

        $parent = DocumentComment::find($this->replyTo);
        if (!$parent || $parent->document_id != $this->documentId) return;

        $this->validate([
            'replyContent' => 'required|string|min:1|max:1000',
        ]);

        DocumentComment::create([
            'document_id' => $this->documentId,
            'user_id' => Auth::id(),
            'parent_id' => $this->replyTo,
            'content' => $this->replyContent,
            'status' => 'visible',
        ]);

        $this->cancelReply();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã trả lời bình luận.']);
    }

    public function startEditComment($commentId)
    {
        if (!Auth::check()) return;

        $comment = DocumentComment::where('id', $commentId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$comment || $comment->document_id != $this->documentId) return;

        if ($comment->created_at->diffInHours(now()) >= 24) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã quá 24 giờ, không thể chỉnh sửa.']);
            return;
        }

        $this->editingCommentId = $comment->id;
        $this->editingContent = $comment->content;
    }

    public function cancelEditComment()
    {
        $this->editingCommentId = null;
        $this->editingContent = '';
    }

    public function updateComment()
    {
        if (!Auth::check()) return;

        $comment = DocumentComment::where('id', $this->editingCommentId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$comment || $comment->document_id != $this->documentId) {
            $this->cancelEditComment();
            return;
        }

        if ($comment->created_at->diffInHours(now()) >= 24) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã quá 24 giờ, không thể chỉnh sửa.']);
            $this->cancelEditComment();
            return;
        }

        $this->validate([
            'editingContent' => 'required|string|min:1|max:1000',
        ]);

        $comment->update(['content' => $this->editingContent]);
        $this->cancelEditComment();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã cập nhật bình luận.']);
    }

    public function deleteComment($commentId)
    {
        if (!Auth::check()) return;

        $comment = DocumentComment::where('id', $commentId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$comment || $comment->document_id != $this->documentId) return;

        $comment->update(['status' => 'hidden']);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xoá bình luận.']);
    }

    public function hideComment($commentId)
    {
        if (!Auth::check() || !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $comment = DocumentComment::where('id', $commentId)
            ->where('document_id', $this->documentId)
            ->first();

        if ($comment) {
            $comment->update(['status' => 'hidden']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã ẩn bình luận.']);
        }
    }

    public function showComment($commentId)
    {
        if (!Auth::check() || !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $comment = DocumentComment::where('id', $commentId)
            ->where('document_id', $this->documentId)
            ->first();

        if ($comment) {
            $comment->update(['status' => 'visible']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã hiện bình luận.']);
        }
    }

    public function hideReview($reviewId)
    {
        if (!Auth::check() || !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $review = DocumentReview::where('id', $reviewId)
            ->where('document_id', $this->documentId)
            ->first();

        if ($review) {
            $review->update(['status' => 'hidden']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã ẩn đánh giá.']);
        }
    }

    public function showReview($reviewId)
    {
        if (!Auth::check() || !$this->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $review = DocumentReview::where('id', $reviewId)
            ->where('document_id', $this->documentId)
            ->first();

        if ($review) {
            $review->update(['status' => 'visible']);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã hiện đánh giá.']);
        }
    }

    private function isAdmin()
    {
        $user = Auth::user();
        if (!$user) return false;
        
        return $user->roles()->where('name', 'admin')->exists();
    }

    public function buyDocument()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $doc = Document::find($this->documentId);
        if (!$doc || !$doc->product || !$doc->product->is_active) return;

        // Check visibility
        if ($doc->visibility === 'private' && $doc->author_id !== Auth::id()) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Tài liệu này hiện đang ở chế độ riêng tư.']);
            return;
        }

        $this->dispatch('openCheckoutModal', documentId: $this->documentId);
    }

    public function openReportModal()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $this->reportReason = 'Bản quyền';
        $this->reportDetails = '';
        $this->showReportModal = true;
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
    }

    public function submitReport()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'reportReason' => 'required|string',
            'reportDetails' => 'nullable|string|max:1000',
        ]);

        \Modules\Document\Models\DocumentReport::create([
            'user_id' => Auth::id(),
            'document_id' => $this->documentId,
            'reason' => $this->reportReason,
            'details' => $this->reportDetails,
            'status' => 'pending'
        ]);

        $this->showReportModal = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Báo cáo vi phạm đã được gửi thành công. Admin sẽ kiểm duyệt tệp này.']);
    }

    public function render()
    {
        $doc = Document::with([
            'author', 'currentVersion.category', 'product', 'tags',
            'reviews.user',
            'comments' => function($q) {
                $q->visible()->whereNull('parent_id')->orderBy('created_at', 'desc');
            },
            'comments.user',
            'comments.replies' => function($q) {
                $q->visible()->orderBy('created_at', 'asc');
            },
            'comments.replies.user',
            'favorites' => function($q) {
                $q->where('user_id', Auth::id());
            }
        ])->find($this->documentId);

        $isBookmarked = Auth::check() && $doc->favorites->isNotEmpty();

        $hasAccess = false;
        $isVip = false;
        if (Auth::check()) {
            $user = Auth::user();
            $isVip = $user->vip_expires_at && $user->vip_expires_at->isFuture();
            if (!$doc->product || !$doc->product->is_active) {
                $hasAccess = true;
            } else {
                $hasAccess = DocumentAccess::where('user_id', Auth::id())
                    ->where('document_id', $doc->id)
                    ->exists();
            }
        }

        if (!$this->hasDownloaded) {
            $this->hasDownloaded = Auth::check() && (
                DocumentDownload::where('document_id', $doc->id)
                    ->where('user_id', Auth::id())
                    ->exists()
                ||
                DocumentAccess::where('user_id', Auth::id())
                    ->where('document_id', $doc->id)
                    ->exists()
            );
        }

        // Check hasReviewed dựa trên loại document
        if (Auth::check()) {
            $isFree = !$doc->product || !$doc->product->is_active;
            
            if ($isFree) {
                // FREE DOC: Check ANY review (chỉ 1 lần)
                $this->hasReviewed = DocumentReview::where('document_id', $doc->id)
                    ->where('user_id', Auth::id())
                    ->exists();
            } else {
                // PAID DOC: Check download gần nhất (cho phép review lại)
                $latestDownload = DocumentDownload::where('user_id', Auth::id())
                    ->where('document_id', $doc->id)
                    ->latest('downloaded_at')
                    ->first();
                
                if ($latestDownload) {
                    $this->hasReviewed = DocumentReview::where('document_download_id', $latestDownload->id)
                        ->exists();
                } else {
                    $this->hasReviewed = false;
                }
            }
        } else {
            $this->hasReviewed = false;
        }

        // Calculate average stars
        $avgRating = $doc->reviews->where('status', 'visible')->avg('rating') ?? 0;
        $totalReviews = $doc->reviews->where('status', 'visible')->count();

        // Related documents
        $relatedDocuments = Document::where('status', 'approved')
            ->whereHas('currentVersion', function($q) use ($doc) {
                $q->where('category_id', $doc->category_id)
                  ->where('visibility', 'public');
            })
            ->where('id', '!=', $doc->id)
            ->orderBy('download_count', 'desc')
            ->limit(4)
            ->get();

        $isAdmin = $this->isAdmin();
        
        $vipQuota = Auth::check() ? (Auth::user()->vip_download_quota ?? 0) : 0;

        // Check if preview file exists
        $previewFileExists = false;
        if ($doc->file_type === 'pdf') {
            $activeVer = $doc->currentVersion;
            if ($activeVer) {
                $watermarkedUrl = ($doc->watermark_status === 'success' && $activeVer->file_watermarked_path) ? $activeVer->file_watermarked_path : null;
                $pdfUrlPath = $hasAccess ? ($watermarkedUrl ?? $activeVer->file_original_path) : ($activeVer->preview_file_path ?? $activeVer->file_original_path);
                if ($pdfUrlPath) {
                    $previewFileExists = $this->checkFileExists($pdfUrlPath);
                }
            }
        } else {
            // Non-pdf, or ZIP
            $previewFileExists = true;
        }

        // Load real ZIP contents if it is a ZIP format
        $zipFilesData = [];
        if ($doc->file_type === 'zip') {
            $activeVer = $doc->currentVersion ?? $doc->latestVersion;
            if ($activeVer) {
                $zipFilesData = \Illuminate\Support\Facades\Cache::rememberForever('zip_structure_' . $activeVer->id, function() use ($doc, $activeVer) {
                    $zipPath = ($doc->watermark_status === 'success' && $activeVer->file_watermarked_path)
                        ? $activeVer->file_watermarked_path
                        : $activeVer->file_original_path;

                    $localZip = null;
                    $zipFiles = [];

                    // Local path
                    $localPath = storage_path('app/public/' . $zipPath);
                    if (file_exists($localPath)) {
                        $localZip = $localPath;
                    }

                    // R2 path
                    if (!$localZip && $zipPath && Storage::disk('r2')->exists($zipPath)) {
                        $tempZip = storage_path('app/temp/' . uniqid('zip_') . '.zip');
                        $dir = dirname($tempZip);
                        if (!is_dir($dir)) mkdir($dir, 0755, true);
                        file_put_contents($tempZip, Storage::disk('r2')->get($zipPath));
                        $localZip = $tempZip;
                    }

                    if ($localZip) {
                        $zip = new \ZipArchive();
                        if ($zip->open($localZip) === TRUE) {
                            for ($i = 0; $i < $zip->numFiles; $i++) {
                                $name = $zip->getNameIndex($i);
                                if (substr($name, -1) === '/') continue;

                                $skipExts = ['png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'webp', 'bmp', 'tiff', 'tif',
                                    'ttf', 'woff', 'woff2', 'eot', 'otf',
                                    'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
                                    'zip', 'rar', '7z', 'tar', 'gz',
                                    'mp3', 'mp4', 'avi', 'mov', 'wav', 'ogg',
                                    'exe', 'dll', 'so', 'dylib', 'bin', 'obj'];
                                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                if (in_array($ext, $skipExts)) continue;

                                $content = $zip->getFromIndex($i);
                                
                                if (!mb_check_encoding($content, 'UTF-8')) {
                                    $detected = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'], true);
                                    if ($detected && $detected !== 'UTF-8') {
                                        $content = mb_convert_encoding($content, 'UTF-8', $detected);
                                    } else {
                                        $zipFiles[$name] = [
                                            'isDir' => false,
                                            'content' => '[Binary file - không thể preview]'
                                        ];
                                        continue;
                                    }
                                }
                                
                                $content = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $content);
                                
                                $lines = explode("\n", $content);
                                if (count($lines) > 50) {
                                    $content = implode("\n", array_slice($lines, 0, 50));
                                    $content .= "\n\n// ... [Hiển thị 50 dòng đầu, vui lòng tải xuống để xem đầy đủ]";
                                } elseif (strlen($content) > 10000) {
                                    $content = substr($content, 0, 10000);
                                    $content .= "\n\n// ... [Nội dung đã được cắt ngắn, vui lòng tải xuống để xem đầy đủ]";
                                }

                                $zipFiles[$name] = [
                                    'isDir' => false,
                                    'content' => $content
                                ];
                            }
                            $zip->close();
                        }
                        if (isset($tempZip) && file_exists($tempZip)) @unlink($tempZip);
                    }
                    return $zipFiles;
                });
            }
        }
        $this->zipFiles = $zipFilesData;

        return view('document::livewire.user.document-detail', [
            'doc' => $doc,
            'isBookmarked' => $isBookmarked,
            'hasAccess' => $hasAccess,
            'isVip' => $isVip,
            'vipQuota' => $vipQuota,
            'hasDownloaded' => $this->hasDownloaded,
            'hasReviewed' => $this->hasReviewed,
            'avgRating' => round($avgRating, 1),
            'totalReviews' => $totalReviews,
            'comments' => $doc->comments,
            'relatedDocuments' => $relatedDocuments,
            'isAdmin' => $isAdmin,
            'zipFiles' => $this->zipFiles,
            'previewFileExists' => $previewFileExists
        ])->layout('layouts.user');
    }

    protected function checkFileExists($path)
    {
        if (!$path) return false;
        
        $cacheKey = 'file_exists_' . md5($path);
        return \Illuminate\Support\Facades\Cache::rememberForever($cacheKey, function() use ($path) {
            try {
                if (Storage::disk('public')->exists($path)) {
                    return true;
                }
                if (Storage::disk('r2')->exists($path)) {
                    return true;
                }
            } catch (\Exception $e) {
                \Log::warning("Failed to check file existence", ['path' => $path, 'error' => $e->getMessage()]);
            }
            return false;
        });
    }
}
