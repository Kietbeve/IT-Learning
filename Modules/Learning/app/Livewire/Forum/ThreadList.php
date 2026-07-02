<?php

namespace Modules\Learning\Livewire\Forum;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Modules\Learning\Models\ForumThread;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Services\ForumService;

class ThreadList extends Component
{
    use WithPagination;

    public string $threadableType = '';
    public int $threadableId = 0;
    public string $sort = 'latest';
    public string $search = '';

    protected $queryString = ['sort', 'search'];

    #[Computed]
    public function threads()
    {
        $threadable = $this->resolveThreadable();
        if (!$threadable) {
            return collect();
        }

        return app(ForumService::class)->getThreadsFor(
            $threadable,
            ['sort' => $this->sort, 'search' => $this->search],
            10
        );
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedSort()
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

    public function toggleBookmark(int $threadId)
    {
        if (!auth()->check()) {
            return $this->redirect(route('auth.google.redirect'));
        }

        $thread = ForumThread::findOrFail($threadId);
        app(ForumService::class)->toggleBookmark($thread, auth()->id());
    }

    private function resolveThreadable()
    {
        return match ($this->threadableType) {
            'roadmap' => Roadmap::find($this->threadableId),
            'lesson' => RoadmapLesson::find($this->threadableId),
            default => null,
        };
    }

    public function render()
    {
        return view('learning::livewire.forum.thread-list');
    }
}
