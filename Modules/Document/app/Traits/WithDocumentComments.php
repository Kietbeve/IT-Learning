<?php

namespace Modules\Document\Traits;

use Illuminate\Support\Facades\Auth;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentComment;

trait WithDocumentComments
{
    public $newComment = '';
    public $replyTo = null;
    public $replyContent = '';
    public $editingCommentId = null;
    public $editingContent = '';
    public $replyToSpecificCommentId = null;

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

        $comment = $this->getCommentForAuthUser($commentId);

        if (!$comment || $comment->document_id != $this->documentId) return;

        if (!$this->isEditableWithin24Hours($comment)) {
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

        $comment = $this->getCommentForAuthUser($this->editingCommentId);

        if (!$comment || $comment->document_id != $this->documentId) {
            $this->cancelEditComment();
            return;
        }

        if (!$this->isEditableWithin24Hours($comment)) {
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

        $comment = $this->getCommentForAuthUser($commentId);

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
}
