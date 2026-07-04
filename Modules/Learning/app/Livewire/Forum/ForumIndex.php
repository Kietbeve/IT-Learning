<?php

namespace Modules\Learning\Livewire\Forum;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Modules\Learning\Models\ForumThread;
use Modules\Learning\Models\ForumPost;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Auth\Models\User;
use Modules\Learning\Services\ForumService;

class ForumIndex extends Component
{
    use WithPagination;

    public string $sort = 'latest';
    public string $search = '';
    public string $roadmapFilter = '';
    public string $statusFilter = '';

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->roadmapFilter = (string) $id;
        }
    }

    protected $queryString = ['sort', 'search', 'roadmapFilter', 'statusFilter'];

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

        if ($this->roadmapFilter) {
            $query->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->where('threadable_type', Roadmap::class)
                        ->where('threadable_id', $this->roadmapFilter);
                })->orWhere(function ($sub) {
                    $sub->where('threadable_type', RoadmapLesson::class)
                        ->whereIn('threadable_id', RoadmapLesson::where('roadmap_id', $this->roadmapFilter)->pluck('id'));
                });
            });
        }

        if ($this->statusFilter === 'unanswered') {
            $query->doesntHave('posts');
        } elseif ($this->statusFilter === 'solved') {
            $query->where('is_answered', true);
        } elseif ($this->statusFilter === 'pinned') {
            $query->where('is_pinned', true);
        }

        $query->orderBy('is_pinned', 'desc');

        return match ($this->sort) {
            'oldest' => $query->orderBy('created_at')->paginate(15),
            'popular' => $query->orderBy('views_count', 'desc')->orderBy('created_at', 'desc')->paginate(15),
            default => $query->orderBy('last_post_at', 'desc')->paginate(15),
        };
    }

    #[Computed]
    public function roadmaps()
    {
        return Roadmap::orderBy('title')->get(['id', 'title']);
    }

    #[Computed]
    public function stats()
    {
        return [
            'threads' => ForumThread::count(),
            'posts' => ForumPost::count(),
            'users' => ForumThread::distinct('user_id')->count('user_id'),
            'solved' => ForumThread::where('is_answered', true)->count(),
        ];
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSort()
    {
        $this->resetPage();
    }

    public function updatedRoadmapFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function toggleLike(int $threadId)
    {
        if (!auth()->check()) {
            return $this->redirect(route('auth.google.redirect'));
        }

        $thread = ForumThread::findOrFail($threadId);
        app(ForumService::class)->toggleLike($thread, auth()->id());
    }

    public function render()
    {
        return view('learning::livewire.forum.forum-index')
            ->layout('layouts.user');
    }
}
