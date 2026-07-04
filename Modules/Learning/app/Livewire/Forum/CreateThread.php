<?php

namespace Modules\Learning\Livewire\Forum;

use Livewire\Component;
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Services\ForumService;

class CreateThread extends Component
{
    public string $threadableType = '';
    public int $threadableId = 0;
    public string $title = '';
    public string $content = '';

    public function mount(string $type, int $id): void
    {
        $this->threadableType = $type;
        $this->threadableId = $id;
    }

    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'content' => 'required|string|min:10',
        ];
    }

    protected function messages(): array
    {
        return [
            'title.required' => 'Vui lòng nhập tiêu đề',
            'title.min' => 'Tiêu đề phải có ít nhất 5 ký tự',
            'content.required' => 'Vui lòng nhập nội dung',
            'content.min' => 'Nội dung phải có ít nhất 10 ký tự',
        ];
    }

    public function submit()
    {
        $this->validate();

        $threadable = $this->resolveThreadable();
        if (!$threadable) {
            session()->flash('error', 'Không tìm thấy nội dung liên kết');
            return;
        }

        app(ForumService::class)->createThread($threadable, [
            'title' => $this->title,
            'content' => $this->content,
        ]);

        session()->flash('message', 'Đã tạo thảo luận thành công!');
        $this->redirect(route('learning.forum.threads.index', [
            'type' => $this->threadableType,
            'id' => $this->threadableId,
        ]));
    }

    public function cancel()
    {
        $this->redirect(route('learning.forum.threads.index', [
            'type' => $this->threadableType,
            'id' => $this->threadableId,
        ]));
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
        return view('learning::livewire.forum.create-thread')
            ->layout('layouts.user');
    }
}
