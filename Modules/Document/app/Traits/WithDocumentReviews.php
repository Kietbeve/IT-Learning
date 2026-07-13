<?php

namespace Modules\Document\Traits;

use Illuminate\Support\Facades\Auth;
use Modules\Document\Models\DocumentReview;
use Modules\Document\Models\DocumentDownload;

trait WithDocumentReviews
{
    public $rating = 5;
    public $reviewContent = '';
    public $hasReviewed = false;
    public $editReviewId = null;
    public $editReviewContent = '';
    public $editRating = 5;

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

        // Chỉ cho phép mỗi user đánh giá 1 lần duy nhất cho mỗi tài liệu
        $hasReviewed = DocumentReview::where('document_id', $this->documentId)
            ->where('user_id', $userId)
            ->exists();

        if ($hasReviewed) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Bạn đã đánh giá tài liệu này rồi. Mỗi tài liệu chỉ được đánh giá 1 lần.']);
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

        $review = $this->getReviewForAuthUser($reviewId);

        if (!$review) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Không tìm thấy đánh giá.']);
            return;
        }

        if (!$this->isEditableWithin24Hours($review)) {
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

        $review = $this->getReviewForAuthUser($this->editReviewId);

        if (!$review) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Không tìm thấy đánh giá.']);
            $this->cancelEdit();
            return;
        }

        if (!$this->isEditableWithin24Hours($review)) {
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

    public function deleteReview($reviewId)
    {
        if (!Auth::check()) return;

        if ($this->isAdmin()) {
            $review = DocumentReview::where('id', $reviewId)
                ->where('document_id', $this->documentId)
                ->first();
        } else {
            $review = $this->getReviewForAuthUser($reviewId);
        }

        if (!$review) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Không tìm thấy đánh giá.']);
            return;
        }

        if (!$this->isAdmin() && !$this->isEditableWithin24Hours($review)) {
            $this->dispatch('notify', ['type' => 'info', 'message' => 'Đã quá 24 giờ, bạn không thể xóa đánh giá này.']);
            return;
        }

        $review->delete();
        
        if (Auth::id() == $review->user_id) {
            $this->hasReviewed = false;
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Xóa đánh giá thành công!']);
    }
}
