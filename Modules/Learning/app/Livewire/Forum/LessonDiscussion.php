<?php

namespace Modules\Learning\Livewire\Forum;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Modules\Learning\Models\ForumThread;
use Modules\Learning\Models\ForumPost;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Services\ForumService;
use Illuminate\Support\Facades\Auth;

class LessonDiscussion extends Component
{
    use WithPagination;

    public int $lessonId;
    public string $search = '';
    public string $sort = 'latest';
    public string $commentSort = 'latest';
    
    // Form tạo thread mới
    public bool $showCreateForm = false;
    public string $newThreadTitle = '';
    public string $newThreadContent = '';
    
    // Reply handling
    public ?int $expandedThreadId = null;
    public ?int $replyToThreadId = null;
    public ?int $replyToPostId = null;
    public string $replyContent = '';
    
    // Edit handling
    public ?int $editingPostId = null;
    public ?int $editingThreadId = null;
    public string $editContent = '';
    public string $editTitle = '';
    
    // Reaction picker
    public ?int $showReactionPickerFor = null;
    public ?string $reactionPickerType = null;
    
    // Report handling
    public ?int $reportingId = null;
    public ?string $reportingType = null;
    public string $reportReason = '';
    public string $reportDescription = '';

    protected $queryString = ['search', 'sort'];

    public function mount(int $lessonId): void
    {
        $this->lessonId = $lessonId;
    }

    #[Computed]
    public function threads()
    {
        $lesson = RoadmapLesson::find($this->lessonId);
        if (!$lesson) {
            return collect();
        }

        $query = ForumThread::query()
            ->where('threadable_type', RoadmapLesson::class)
            ->where('threadable_id', $this->lessonId)
            ->with(['user', 'posts.user', 'posts.replies.user']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('content', 'like', "%{$this->search}%");
            });
        }

        $query->orderBy('is_pinned', 'desc');

        return match ($this->sort) {
            'oldest' => $query->orderBy('created_at')->paginate(5),
            'unanswered' => $query->where('is_answered', false)->orderBy('created_at', 'desc')->paginate(5),
            default => $query->orderBy('last_post_at', 'desc')->orderBy('created_at', 'desc')->paginate(5),
        };
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function toggleCreateForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
        if (!$this->showCreateForm) {
            $this->resetCreateForm();
        }
    }

    public function createThread()
    {
        if (!Auth::check()) {
            return $this->redirect(route('auth.google.redirect'));
        }

        $this->validate([
            'newThreadTitle' => 'required|string|min:5|max:200',
            'newThreadContent' => 'required|string|min:10',
        ], [
            'newThreadTitle.required' => 'Vui lòng nhập tiêu đề',
            'newThreadTitle.min' => 'Tiêu đề phải có ít nhất 5 ký tự',
            'newThreadTitle.max' => 'Tiêu đề không được vượt quá 200 ký tự',
            'newThreadContent.required' => 'Vui lòng nhập nội dung',
            'newThreadContent.min' => 'Nội dung phải có ít nhất 10 ký tự',
        ]);

        $lesson = RoadmapLesson::findOrFail($this->lessonId);
        $mentions = app(ForumService::class)->extractMentions($this->newThreadContent);

        ForumThread::create([
            'user_id' => Auth::id(),
            'title' => $this->newThreadTitle,
            'content' => $this->newThreadContent,
            'threadable_type' => RoadmapLesson::class,
            'threadable_id' => $lesson->id,
            'last_post_at' => now(),
        ]);

        $this->resetCreateForm();
        $this->showCreateForm = false;
        session()->flash('message', 'Đã tạo thảo luận mới!');
        $this->resetPage();
    }

    public function toggleExpand(int $threadId)
    {
        if ($this->expandedThreadId === $threadId) {
            $this->expandedThreadId = null;
        } else {
            $this->expandedThreadId = $threadId;
            app(ForumService::class)->incrementView(ForumThread::findOrFail($threadId));
        }
    }

    public function toggleExpandAndReply(int $threadId)
    {
        $this->expandedThreadId = $threadId;
        $this->replyToThreadId = $threadId;
        app(ForumService::class)->incrementView(ForumThread::findOrFail($threadId));
    }

    public function startReply(int $threadId, ?int $postId = null)
    {
        if (!Auth::check()) {
            return $this->redirect(route('auth.google.redirect'));
        }
        $this->replyToThreadId = $threadId;
        $this->replyToPostId = $postId;
        $this->expandedThreadId = $threadId;
    }

    public function cancelReply()
    {
        $this->replyToThreadId = null;
        $this->replyToPostId = null;
        $this->replyContent = '';
    }

    public function submitReply()
    {
        if (!Auth::check()) {
            return $this->redirect(route('auth.google.redirect'));
        }

        $this->validate([
            'replyContent' => 'required|string|min:2',
        ], [
            'replyContent.required' => 'Vui lòng nhập nội dung trả lời',
            'replyContent.min' => 'Nội dung phải có ít nhất 2 ký tự',
        ]);

        $thread = ForumThread::findOrFail($this->replyToThreadId);
        if ($thread->is_locked) {
            session()->flash('error', 'Chủ đề này đã bị khóa');
            return;
        }

        $mentions = app(ForumService::class)->extractMentions($this->replyContent);
        $post = app(ForumService::class)->createReply($thread, ['content' => $this->replyContent], $this->replyToPostId);
        if (!empty($mentions)) {
            $post->update(['mentions' => $mentions]);
        }

        $this->replyContent = '';
        $this->replyToPostId = null;
        $this->replyToThreadId = null;
        session()->flash('message', 'Đã gửi trả lời!');
    }

    public function getSortedPosts($threadId)
    {
        $thread = ForumThread::findOrFail($threadId);
        return app(ForumService::class)->getSortedPosts($thread, $this->commentSort);
    }

    public function toggleBookmark(int $threadId)
    {
        if (!Auth::check()) {
            return $this->redirect(route('auth.google.redirect'));
        }
        $thread = ForumThread::findOrFail($threadId);
        app(ForumService::class)->toggleBookmark($thread, Auth::id());
    }

    public function markBestAnswer(int $postId)
    {
        if (!Auth::check()) {
            return $this->redirect(route('auth.google.redirect'));
        }

        $post = ForumPost::findOrFail($postId);
        $thread = $post->thread;

        if ($thread->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            session()->flash('error', 'Bạn không có quyền thực hiện hành động này');
            return;
        }

        app(ForumService::class)->markBestAnswer($post);
        session()->flash('message', 'Đã đánh dấu câu trả lời hay nhất!');
    }

    public function deletePost(int $postId)
    {
        if (!Auth::check()) {
            return;
        }

        $post = ForumPost::withTrashed()->findOrFail($postId);
        if ($post->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            session()->flash('error', 'Bạn không có quyền xóa');
            return;
        }

        app(ForumService::class)->deletePost($post);
        session()->flash('message', 'Đã xóa bình luận');
    }

    public function deleteThread(int $threadId)
    {
        if (!Auth::check()) {
            return;
        }

        $thread = ForumThread::withTrashed()->findOrFail($threadId);
        if ($thread->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            session()->flash('error', 'Bạn không có quyền xóa');
            return;
        }

        app(ForumService::class)->deleteThread($thread);
        session()->flash('message', 'Đã xóa thảo luận');
        $this->expandedThreadId = null;
    }

    private function resetCreateForm()
    {
        $this->newThreadTitle = '';
        $this->newThreadContent = '';
        $this->resetValidation();
    }

    // ===== REACTION METHODS =====
    
    public function toggleReaction(string $type, int $id, string $reactionType = 'like')
    {
        if (!Auth::check()) {
            return $this->redirect(route('auth.google.redirect'));
        }

        $reactable = $type === 'thread' 
            ? ForumThread::findOrFail($id)
            : ForumPost::findOrFail($id);

        app(ForumService::class)->toggleReaction($reactable, Auth::id(), $reactionType);
        $this->hideReactionPicker();
    }

    public function showReactionPicker(int $id, string $type)
    {
        $this->showReactionPickerFor = $id;
        $this->reactionPickerType = $type;
    }

    public function hideReactionPicker()
    {
        $this->showReactionPickerFor = null;
        $this->reactionPickerType = null;
    }

    // ===== EDIT METHODS =====
    
    public function startEditPost(int $postId)
    {
        if (!Auth::check()) {
            return;
        }

        $post = ForumPost::findOrFail($postId);
        if ($post->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            session()->flash('error', 'Bạn không có quyền chỉnh sửa');
            return;
        }

        $this->editingPostId = $postId;
        $this->editContent = $post->content;
    }

    public function startEditThread(int $threadId)
    {
        if (!Auth::check()) {
            return;
        }

        $thread = ForumThread::findOrFail($threadId);
        if ($thread->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            session()->flash('error', 'Bạn không có quyền chỉnh sửa');
            return;
        }

        $this->editingThreadId = $threadId;
        $this->editTitle = $thread->title;
        $this->editContent = $thread->content;
    }

    public function saveEditPost()
    {
        $this->validate([
            'editContent' => 'required|string|min:2',
        ], [
            'editContent.required' => 'Vui lòng nhập nội dung',
            'editContent.min' => 'Nội dung phải có ít nhất 2 ký tự',
        ]);

        $post = ForumPost::findOrFail($this->editingPostId);
        app(ForumService::class)->editPost($post, $this->editContent);

        $this->cancelEdit();
        session()->flash('message', 'Đã cập nhật bình luận!');
    }

    public function saveEditThread()
    {
        $this->validate([
            'editTitle' => 'required|string|min:5|max:200',
            'editContent' => 'required|string|min:10',
        ], [
            'editTitle.required' => 'Vui lòng nhập tiêu đề',
            'editTitle.min' => 'Tiêu đề phải có ít nhất 5 ký tự',
            'editContent.required' => 'Vui lòng nhập nội dung',
            'editContent.min' => 'Nội dung phải có ít nhất 10 ký tự',
        ]);

        $thread = ForumThread::findOrFail($this->editingThreadId);
        app(ForumService::class)->editThread($thread, [
            'title' => $this->editTitle,
            'content' => $this->editContent,
        ]);

        $this->cancelEdit();
        session()->flash('message', 'Đã cập nhật thảo luận!');
    }

    public function cancelEdit()
    {
        $this->editingPostId = null;
        $this->editingThreadId = null;
        $this->editContent = '';
        $this->editTitle = '';
        $this->resetValidation();
    }

    // ===== PIN METHODS =====
    
    public function togglePinPost(int $postId)
    {
        if (!Auth::check()) {
            return;
        }

        $post = ForumPost::findOrFail($postId);
        $thread = $post->thread;

        if ($thread->user_id !== Auth::id() && !Auth::user()?->hasRole('admin')) {
            session()->flash('error', 'Chỉ người tạo thread hoặc admin mới có quyền ghim bình luận');
            return;
        }

        if ($post->is_pinned) {
            app(ForumService::class)->unpinPost($post);
            session()->flash('message', 'Đã bỏ ghim bình luận');
        } else {
            app(ForumService::class)->pinPost($post);
            session()->flash('message', 'Đã ghim bình luận');
        }
    }

    // ===== REPORT METHODS =====
    
    public function startReport(int $id, string $type)
    {
        if (!Auth::check()) {
            return $this->redirect(route('auth.google.redirect'));
        }

        $this->reportingId = $id;
        $this->reportingType = $type;
    }

    public function submitReport()
    {
        $this->validate([
            'reportReason' => 'required|string',
            'reportDescription' => 'nullable|string|max:500',
        ], [
            'reportReason.required' => 'Vui lòng chọn lý do báo cáo',
            'reportDescription.max' => 'Mô tả không được vượt quá 500 ký tự',
        ]);

        $reportable = $this->reportingType === 'thread'
            ? ForumThread::findOrFail($this->reportingId)
            : ForumPost::findOrFail($this->reportingId);

        app(ForumService::class)->reportContent(
            $reportable,
            Auth::id(),
            $this->reportReason,
            $this->reportDescription
        );

        $this->cancelReport();
        session()->flash('message', 'Đã gửi báo cáo. Admin sẽ xem xét trong thời gian sớm nhất.');
    }

    public function cancelReport()
    {
        $this->reportingId = null;
        $this->reportingType = null;
        $this->reportReason = '';
        $this->reportDescription = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('learning::livewire.forum.lesson-discussion');
    }
}
