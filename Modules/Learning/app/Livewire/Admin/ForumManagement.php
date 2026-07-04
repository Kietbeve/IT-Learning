<?php

namespace Modules\Learning\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Modules\Learning\Models\ForumThread;
use Modules\Learning\Models\ForumPost;
use Modules\Learning\Models\ForumLike;
use Modules\Learning\Models\ForumBookmark;
use Modules\Auth\Models\User;
use Modules\Learning\Services\ForumService;

#[Layout('layouts.admin')]
class ForumManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';
    public string $sortField = 'last_post_at';
    public string $sortDirection = 'desc';
    public int $perPage = 15;

    // Thread detail modal
    public ?int $detailThreadId = null;
    public bool $showDetail = false;

    // Reply
    public string $replyContent = '';
    public ?int $replyToPostId = null;

    // Delete confirmation
    public ?int $deleteId = null;
    public bool $showDeleteConfirm = false;
    public string $deleteType = 'thread'; // thread or post
    public ?int $deletePostId = null;

    protected $queryString = ['search', 'statusFilter', 'sortField', 'sortDirection'];

    #[Computed]
    public function threads()
    {
        $query = ForumThread::query()->with('user', 'threadable');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        if ($this->statusFilter === 'pinned') {
            $query->where('is_pinned', true);
        } elseif ($this->statusFilter === 'locked') {
            $query->where('is_locked', true);
        } elseif ($this->statusFilter === 'answered') {
            $query->where('is_answered', true);
        } elseif ($this->statusFilter === 'unanswered') {
            $query->where('is_answered', false);
        }

        $query->orderBy($this->sortField, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    #[Computed]
    public function detailThread()
    {
        if (!$this->detailThreadId) return null;
        return ForumThread::with(['user', 'posts.user', 'posts.replies.user'])->find($this->detailThreadId);
    }

    #[Computed]
    public function stats()
    {
        return [
            'threads' => ForumThread::count(),
            'posts' => ForumPost::count(),
            'users' => ForumThread::distinct('user_id')->count('user_id'),
            'pinned' => ForumThread::where('is_pinned', true)->count(),
            'locked' => ForumThread::where('is_locked', true)->count(),
            'answered' => ForumThread::where('is_answered', true)->count(),
            'likes' => ForumLike::count(),
            'bookmarks' => ForumBookmark::count(),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function replyTo(int $postId)
    {
        $this->replyToPostId = $postId;
    }

    public function cancelReply()
    {
        $this->replyToPostId = null;
        $this->replyContent = '';
    }

    public function submitReply()
    {
        $this->validate(['replyContent' => 'required|string|min:2']);

        $thread = ForumThread::findOrFail($this->detailThreadId);

        if ($thread->is_locked) {
            $this->dispatch('notify', type: 'error', message: 'Chủ đề đã bị khóa');
            return;
        }

        app(ForumService::class)->createReply($thread, [
            'content' => $this->replyContent,
        ], $this->replyToPostId);

        $this->replyContent = '';
        $this->replyToPostId = null;
        $this->dispatch('notify', type: 'success', message: 'Đã gửi trả lời');
    }

    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function viewThread(int $id)
    {
        $this->detailThreadId = $id;
        $this->showDetail = true;
    }

    public function closeDetail()
    {
        $this->showDetail = false;
        $this->detailThreadId = null;
    }

    public function togglePin(int $id)
    {
        $thread = ForumThread::findOrFail($id);
        $thread->update(['is_pinned' => !$thread->is_pinned]);
        $this->dispatch('notify', type: 'success', message: $thread->is_pinned ? 'Đã ghim thảo luận' : 'Đã bỏ ghim');
    }

    public function toggleLock(int $id)
    {
        $thread = ForumThread::findOrFail($id);
        $thread->update(['is_locked' => !$thread->is_locked]);
        $this->dispatch('notify', type: 'success', message: $thread->is_locked ? 'Đã khóa thảo luận' : 'Đã mở khóa');
    }

    public function confirmDeleteThread(int $id)
    {
        $this->deleteId = $id;
        $this->deleteType = 'thread';
        $this->showDeleteConfirm = true;
    }

    public function confirmDeletePost(int $id)
    {
        $this->deletePostId = $id;
        $this->deleteType = 'post';
        $this->showDeleteConfirm = true;
    }

    public function executeDelete()
    {
        try {
            if ($this->deleteType === 'thread' && $this->deleteId) {
                $thread = ForumThread::withTrashed()->findOrFail($this->deleteId);
                app(ForumService::class)->deleteThread($thread);
                $this->dispatch('notify', type: 'success', message: 'Đã xóa thảo luận');
            } elseif ($this->deleteType === 'post' && $this->deletePostId) {
                $post = ForumPost::withTrashed()->findOrFail($this->deletePostId);
                app(ForumService::class)->deletePost($post);
                $this->dispatch('notify', type: 'success', message: 'Đã xóa bình luận');
            }
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Lỗi: ' . $e->getMessage());
        }

        $this->showDeleteConfirm = false;
        $this->deleteId = null;
        $this->deletePostId = null;
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirm = false;
        $this->deleteId = null;
        $this->deletePostId = null;
    }

    public function render()
    {
        return view('learning::livewire.admin.forum-management', [
            'pageTitle' => 'Quản lý Diễn đàn',
        ]);
    }
}
