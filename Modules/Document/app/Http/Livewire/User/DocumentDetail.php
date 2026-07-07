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
    public $showVipConfirmModal = false;

    public function promptVipDownload()
    {
        if (!Auth::check()) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Vui lòng đăng nhập để sử dụng tính năng này.']);
            return;
        }

        $subscriptionService = app(\Modules\Payment\Services\SubscriptionService::class);
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
        // Để JS tự đóng sau 1.5s
        // $this->showVipConfirmModal = false;
        
        if (!Auth::check()) return;

        $userId = Auth::id();
        $doc = Document::with('product')->find($this->documentId);
        
        if (!$doc || !$doc->product) return;

        $subscriptionService = app(\Modules\Payment\Services\SubscriptionService::class);
        $user = Auth::user();
        $isVipActive = $subscriptionService->isVipActive($user);
        $hasQuota = $user->vip_download_quota > 0;

        if ($isVipActive && $hasQuota) {
            try {
                DB::beginTransaction();
                
                $orderService = app(\Modules\Payment\Services\OrderService::class);
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

                // Cập nhật chuông thông báo trên UI của người mua ngay lập tức (cái này sẽ tự bung toast của hệ thống)
                $this->dispatch('new-notification');
                
                // Dispatch event để JS đóng modal sau 1.5s
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

        $doc = Document::find($this->documentId);
        if (!$doc) return;

        $doc->toggleFavoriteForUser(Auth::id());
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

        if ($userId === $doc->author_id) {
            $hasAccess = true;
        } elseif ($isPaid) {
            $access = DocumentAccess::where('user_id', $userId)
                ->where('document_id', $doc->id)
                ->first();

        if ($access) {
            $orderItemId = $access->order_item_id;
            $hasAccess = true;
        } else {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn cần mua tài liệu này trước khi tải xuống.']);
            return;
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
            
            // Xóa quyền truy cập ngay sau khi tải (Pay-Per-Download)
            if ($isPaid && $userId !== $doc->author_id) {
                \Modules\Payment\Models\DocumentAccess::where('user_id', $userId)
                    ->where('document_id', $doc->id)
                    ->delete();
            }

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
        $this->newReview = '';
        $this->rating = 5;
        $this->hasReviewed = true;

        // $this->dispatch('notify', ['type' => 'success', 'message' => 'Gửi đánh giá thành công!']);
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
        // $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã đăng bình luận.']);
    }

    public $replyToSpecificCommentId = null;

    public function startReply($commentId, $userName = null, $replyId = null)
    {
        if (!Auth::check()) return;

        $comment = DocumentComment::find($commentId);
        if (!$comment || $comment->document_id != $this->documentId) return;

        $this->replyTo = $commentId;
        $this->replyToSpecificCommentId = $replyId;
        
        if ($userName) {
            $this->replyContent = '@' . $userName . ': ';
        } elseif ($comment->user) {
            $this->replyContent = '@' . $comment->user->name . ': ';
        } else {
            $this->replyContent = '';
        }
    }

    public function cancelReply()
    {
        $this->replyTo = null;
        $this->replyToSpecificCommentId = null;
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

        $newComment = DocumentComment::create([
            'document_id' => $this->documentId,
            'user_id' => Auth::id(),
            'parent_id' => $this->replyTo,
            'content' => $this->replyContent,
            'status' => 'visible',
        ]);

        // Determine the target user to notify
        $targetUser = null;
        if ($this->replyToSpecificCommentId) {
            $specificReply = DocumentComment::find($this->replyToSpecificCommentId);
            if ($specificReply && $specificReply->user) {
                $targetUser = $specificReply->user;
            }
        } elseif ($parent->user) {
            $targetUser = $parent->user;
        }

        // Send notification to the target user
        if ($targetUser && $targetUser->id !== Auth::id()) {
            $doc = Document::find($this->documentId);
            if ($doc) {
                event(new \Modules\Document\Events\CommentReplied(Auth::user(), $doc, $newComment->id, $targetUser));
                $this->dispatch('new-notification'); // trigger bell update
            }
        }

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
            $isVip = $user->checkAndExpireVip();
            if (!$doc->product || !$doc->product->is_active || Auth::id() === $doc->author_id) {
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

        // Get current document attributes
        $currentCategoryId = $doc->currentVersion ? $doc->currentVersion->category_id : null;
        $currentSubjectId = $doc->currentVersion ? $doc->currentVersion->subject_id : null;
        $currentTagIds = $doc->tags->pluck('id')->toArray();

        // Fetch candidates that share at least one criteria (category, subject, or tag)
        $candidates = Document::where('status', 'approved')
            ->whereHas('currentVersion', function($q) {
                $q->where('visibility', 'public');
            })
            ->where('id', '!=', $doc->id)
            ->where(function ($query) use ($currentCategoryId, $currentSubjectId, $currentTagIds) {
                if ($currentCategoryId || $currentSubjectId) {
                    $query->whereHas('currentVersion', function ($q) use ($currentCategoryId, $currentSubjectId) {
                        $q->where(function($sq) use ($currentCategoryId, $currentSubjectId) {
                            if ($currentCategoryId) {
                                $sq->where('category_id', $currentCategoryId);
                            }
                            if ($currentSubjectId) {
                                $sq->orWhere('subject_id', $currentSubjectId);
                            }
                        });
                    });
                }
                
                if (!empty($currentTagIds)) {
                    $query->orWhereHas('tags', function ($q) use ($currentTagIds) {
                        $q->whereIn('tags.id', $currentTagIds);
                    });
                }
            })
            ->with(['tags', 'author', 'currentVersion'])
            ->get();

        // Calculate relevance score for each candidate
        $relatedDocuments = $candidates->map(function ($related) use ($currentCategoryId, $currentSubjectId, $currentTagIds) {
            $score = 0;
            $relatedVer = $related->currentVersion;

            // 1. Cùng danh mục: +3
            if ($currentCategoryId && $relatedVer && $relatedVer->category_id === $currentCategoryId) {
                $score += 3;
                
                // Tải nhiều trong danh mục: +2 (Give up to 2 points based on download count)
                if ($related->download_count >= 50) {
                    $score += 2;
                } elseif ($related->download_count >= 20) {
                    $score += 1;
                }
            }

            // 2. Cùng môn học: +4
            if ($currentSubjectId && $relatedVer && $relatedVer->subject_id === $currentSubjectId) {
                $score += 4;
            }

            // 3. Cùng tags: +5 for each matching tag
            if (!empty($currentTagIds)) {
                $relatedTagIds = $related->tags->pluck('id')->toArray();
                $matchingTags = array_intersect($currentTagIds, $relatedTagIds);
                $score += (count($matchingTags) * 5);
            }

            $related->relevance_score = $score;
            return $related;
        })
        ->filter(function ($related) {
            return $related->relevance_score > 0;
        })
        ->sortByDesc(function ($related) {
            // Sort by score first, then download_count as tie-breaker
            return [$related->relevance_score, $related->download_count];
        })
        ->take(4);

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
                $zipService = app(\Modules\Document\Services\ZipPreviewService::class);
                $zipFilesData = $zipService->getZipStructure($doc, $activeVer);
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
