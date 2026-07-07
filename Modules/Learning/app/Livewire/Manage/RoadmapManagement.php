<?php

namespace Modules\Learning\Livewire\Manage;

use Livewire\Component;
use Livewire\Attributes\Layout;
use Modules\Learning\Models\Roadmap; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

#[Layout('layouts.contributor')]
class RoadmapManagement extends Component
{
    // FIX WARNING P1132: Thêm kiểu dữ liệu int hoặc null cho roadmapId
    public ?int $roadmapId = null;
    
    public string $title = '';
    public string $slug = '';
    public string $short_description = '';
    public string $description = '';
    public string $level = 'beginner';
    public string $visibility = 'public';
    public string $status = 'draft';

    // Trạng thái giao diện
    public bool $isOpenForm = false;
    public bool $isEditMode = false;
    public string $search = '';

    // Rules kiểm tra dữ liệu thật
    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'slug' => 'required|string|unique:roadmaps,slug,' . $this->roadmapId,
            'short_description' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|string',
            'visibility' => 'required|in:public,private',
            
            // SỬA LỖI TẠI ĐÂY: Đổi in:0,1 thành in:draft,approved cho khớp Database
            'status' => 'required|in:draft,approved',
        ];
    }

    // FIX WARNING P1132: Thêm kiểu dữ liệu string cho $value
    public function updatedTitle(string $value): void
    {
        $this->slug = Str::slug($value);
    }

    public function openCreateForm(): void
    {
        $this->resetForm();
        $this->isOpenForm = true;
        $this->isEditMode = false;
    }

    // FIX WARNING P1132: Thêm kiểu dữ liệu int cho $id
    public function openEditForm(int $id): void
    {
        $this->resetForm();
        $roadmap = Roadmap::findOrFail($id);
        
        $this->roadmapId = $roadmap->id;
        $this->title = $roadmap->title;
        $this->slug = $roadmap->slug;
        $this->short_description = $roadmap->short_description ?? '';
        $this->description = $roadmap->description ?? '';
        $this->level = $roadmap->level ?? 'beginner';
        $this->visibility = $roadmap->visibility ?? 'public';
        $this->status = $roadmap->status;

        $this->isOpenForm = true;
        $this->isEditMode = true;
    }

    public function closeForm(): void
    {
        $this->isOpenForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->roadmapId = null;
        $this->title = '';
        $this->slug = '';
        $this->short_description = '';
        $this->description = '';
        $this->level = 'beginner';
        $this->visibility = 'public';
        $this->status = 'draft';
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditMode) {
            $roadmap = Roadmap::findOrFail($this->roadmapId);
            $roadmap->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'short_description' => $this->short_description,
                'description' => $this->description,
                'level' => $this->level,
                'visibility' => $this->visibility,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Cập nhật lộ trình thành công!');
        } else {
            Roadmap::create([
                'public_id' => 'RM-' . strtoupper(Str::random(8)),
                // FIX ERROR P1013: Sử dụng Auth::id() thay cho auth()->id() để Intelephense nhận diện đúng phương thức
                'author_id' => Auth::id() ?? 1, 
                'category_id' => null, 
                'title' => $this->title,
                'slug' => $this->slug,
                'short_description' => $this->short_description,
                'description' => $this->description,
                'level' => $this->level,
                'visibility' => $this->visibility,
                'status' => $this->status,
            ]);
            session()->flash('message', 'Tạo mới lộ trình thành công!');
        }

        $this->closeForm();
    }

    // THÊM MỚI HÀM NÀY: Dùng để click nút gạt Ẩn/Hiện ngay trên danh sách Lộ trình
    public function toggleStatus(int $id): void
    {
        $roadmap = Roadmap::findOrFail($id);
        
        // Đảo trạng thái: nếu đang approved thì về draft và ngược lại
        $roadmap->status = ($roadmap->status === 'approved') ? 'draft' : 'approved';
        $roadmap->save();
        
        session()->flash('message', 'Đã cập nhật trạng thái hiển thị thành công!');
    }

    // FIX WARNING P1132: Thêm kiểu dữ liệu int cho $id
    public function deleteRoadmap(int $id): void
    {
        $roadmap = Roadmap::findOrFail($id);
        $roadmap->delete(); 
        session()->flash('message', 'Đã xóa tạm thời lộ trình vào thùng rác!');
    }

    public function render()
    {
        $roadmaps = Roadmap::where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->get();

        $view = view('learning::manage.management-roadmap', [
            'roadmaps' => $roadmaps
        ]);

        /** @var mixed $view */
        return $view->extends('learning::layouts.master')->section('content');
    }
}