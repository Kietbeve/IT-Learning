<?php

namespace Modules\Learning\Livewire\Manage;

use Livewire\Component;
use Illuminate\Support\Str;

// Import các Model chuẩn của bạn
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapLesson; 

class ManagementDetail extends Component
{
    // Định kiểu dữ liệu chuẩn PHP 8.4
    public ?Roadmap $roadmap = null;
    public string $search = '';
    public bool $isOpenForm = false;
    public bool $isEditMode = false;

    // Các thuộc tính phục vụ Form Bài học (RoadmapLesson)
    public ?int $lessonId = null;
    public string $title = '';
    public ?string $description = null; 
    public int $sort_order = 1;
    public ?int $section_id = null; // Có thể để nullable nếu chưa chọn chương
    public int $is_published = 1;

    // Mảng chứa danh sách chương mục (Phục vụ select box trong form nếu cần)
    public array $sections = [];

    // Quy tắc kiểm tra dữ liệu đầu vào (Validation)
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'section_id' => 'nullable|integer',
            'is_published' => 'required|in:0,1',
        ];
    }

    // Thông báo lỗi giao diện bằng tiếng Việt
    protected array $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề bài học.',
        'sort_order.required' => 'Vui lòng nhập thứ thứ tự hiển thị.',
    ];

    public function mount(): void
    {
        // 🌟 LẤY CHÍNH XÁC ID TỪ TRANG 1 TRUYỀN SANG
        $roadmapId = request()->query('id') ?? request()->route('id');
        
        // Nếu không có ID từ trang 1, đá ngược user về lại trang danh sách lộ trình
        if (!$roadmapId) {
            redirect()->to('/manage/roadmap');
            return;
        }
        
        $this->roadmap = Roadmap::findOrFail($roadmapId);
    }

    public function openCreateForm(): void
    {
        $this->resetValidation();
        $this->resetInputFields();
        $this->isOpenForm = true;
        $this->isEditMode = false;
    }

    public function openEditForm(int $id): void
    {
        $this->resetValidation();
        $lesson = RoadmapLesson::findOrFail($id);
        
        $this->lessonId = $lesson->id;
        $this->title = $lesson->title;
        $this->description = $lesson->description; 
        $this->sort_order = $lesson->sort_order;
        $this->section_id = $lesson->section_id;
        $this->is_published = $lesson->is_published ? 1 : 0;

        $this->isOpenForm = true;
        $this->isEditMode = true;
    }

    public function closeForm(): void
    {
        $this->resetInputFields();
        $this->isOpenForm = false;
    }

    private function resetInputFields(): void
    {
        $this->lessonId = null;
        $this->title = '';
        $this->description = null;
        $this->sort_order = 1;
        $this->section_id = null;
        $this->is_published = 1;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->isEditMode) {
            // Thực hiện Cập nhật bài học
            $lesson = RoadmapLesson::findOrFail($this->lessonId);
            $lesson->update([
                'title' => $this->title,
                'slug' => Str::slug($this->title), // 🔥 Sửa lỗi thiếu slug khi cập nhật
                'description' => $this->description,
                'sort_order' => $this->sort_order,
                'section_id' => $this->section_id,
                'is_published' => $this->is_published,
            ]);
            session()->flash('message', '🎉 Cập nhật bài học thành công!');
        } else {
            // Thực hiện Thêm mới bài học
            RoadmapLesson::create([
                'roadmap_id' => $this->roadmap->id, // Tự động ăn theo lộ trình đã chọn ở Trang 1
                'title' => $this->title,
                'slug' => Str::slug($this->title), // 🔥 Sửa lỗi thiếu slug khi tạo mới
                'description' => $this->description,
                'sort_order' => $this->sort_order,
                'section_id' => $this->section_id,
                'is_published' => $this->is_published,
            ]);
            session()->flash('message', '✨ Thêm bài học mới thành công!');
        }

        $this->closeForm();
    }

    public function deleteLesson(int $id): void
    {
        $lesson = RoadmapLesson::findOrFail($id);
        $lesson->delete();
        session()->flash('message', '🗑️ Đã xóa bài học thành công khỏi hệ thống!');
    }

    public function render()
    {
        // 🌟 Chỉ lấy danh sách bài học thuộc Lộ trình đã chọn từ Trang 1
        $lessons = RoadmapLesson::where('roadmap_id', $this->roadmap->id)
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy('sort_order', 'asc')
            ->get();

        $view = view('learning::manage.management-detail', [
            'lessons' => $lessons
        ]);

        /** @var mixed $view */
        return $view->extends('learning::layouts.master')->section('content');
    }
}