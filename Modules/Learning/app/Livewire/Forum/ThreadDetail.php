<?php

namespace Modules\Learning\Livewire\Forum;

use Livewire\Component;
use Modules\Learning\Models\ForumThread;
use Modules\Learning\Models\ForumPost;
use Modules\Learning\Services\ForumService;

class ThreadDetail extends Component
{
    public int $threadId;
    public string $replyContent = '';
    public ?int $replyToId = null;

    public function mount(int $threadId)
    {
        $this->threadId = $threadId;
        app(ForumService::class)->incrementView(ForumThread::findOrFail($threadId));
    }

    public function getThreadProperty()
    {
        return app(ForumService::class)->findThread($this->threadId);
    }

    public function submitReply()
    {
        $this->validate([
            'replyContent' => 'required|string|min:2',
        ]);

        $thread = ForumThread::findOrFail($this->threadId);

        if ($thread->is_locked) {
            session()->flash('error', 'Chủ đề này đã bị khóa, không thể trả lời');
            return;
        }

        app(ForumService::class)->createReply($thread, [
            'content' => $this->replyContent,
        ], $this->replyToId);

        $this->replyContent = '';
        $this->replyToId = null;
        session()->flash('message', 'Đã gửi trả lời!');
    }

    public function replyTo(int $postId)
    {
        $this->replyToId = $postId;
    }

    public function cancelReply()
    {
        $this->replyToId = null;
        $this->replyContent = '';
    }

    public function toggleLike($type, int $id)
    {
        if (!auth()->check()) {
            return $this->redirect(route('auth.google.redirect'));
        }

        $likeable = $type === 'thread'
            ? ForumThread::findOrFail($id)
            : ForumPost::findOrFail($id);

        app(ForumService::class)->toggleLike($likeable, auth()->id());
    }

    public function markBestAnswer(int $postId)
    {
        $thread = ForumThread::findOrFail($this->threadId);
        if ($thread->user_id !== auth()->id() && !auth()->user()?->hasRole('admin')) {
            session()->flash('error', 'Bạn không có quyền thực hiện hành động này');
            return;
        }

        $post = ForumPost::findOrFail($postId);
        app(ForumService::class)->markBestAnswer($post);
        session()->flash('message', 'Đã đánh dấu câu trả lời hay nhất!');
    }

    public function deletePost(int $postId)
    {
        $post = ForumPost::withTrashed()->findOrFail($postId);

        if ($post->user_id !== auth()->id() && !auth()->user()?->hasRole('admin')) {
            session()->flash('error', 'Bạn không có quyền xóa');
            return;
        }

        app(ForumService::class)->deletePost($post);
        session()->flash('message', 'Đã xóa bình luận');
    }

    public function togglePin()
    {
        if (!auth()->user()?->hasRole('admin')) {
            session()->flash('error', 'Chỉ admin mới có quyền ghim thảo luận');
            return;
        }

        $thread = ForumThread::findOrFail($this->threadId);
        $thread->update(['is_pinned' => !$thread->is_pinned]);

        session()->flash('message', $thread->is_pinned ? 'Đã ghim thảo luận' : 'Đã bỏ ghim thảo luận');
    }

    public function toggleLock()
    {
        if (!auth()->user()?->hasRole('admin')) {
            session()->flash('error', 'Chỉ admin mới có quyền khóa thảo luận');
            return;
        }

        $thread = ForumThread::findOrFail($this->threadId);
        $thread->update(['is_locked' => !$thread->is_locked]);

        session()->flash('message', $thread->is_locked ? 'Đã khóa thảo luận' : 'Đã mở khóa thảo luận');
    }

    public function deleteThread()
    {
        $thread = ForumThread::withTrashed()->findOrFail($this->threadId);

        if ($thread->user_id !== auth()->id() && !auth()->user()?->hasRole('admin')) {
            session()->flash('error', 'Bạn không có quyền xóa');
            return;
        }

        app(ForumService::class)->deleteThread($thread);
        session()->flash('message', 'Đã xóa thảo luận');

        $this->redirect(route('learning.forum.threads.index', [
            'type' => class_basename($thread->threadable_type),
            'id' => $thread->threadable_id,
        ]));
    }

    public function render()
    {
        return view('learning::livewire.forum.thread-detail')
            ->layout('layouts.user');
    }
}
