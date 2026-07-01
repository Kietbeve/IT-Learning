<?php

namespace Modules\Learning\Livewire\Manage;

use Livewire\Component;
use Livewire\WithFileUploads;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ManagementLesson extends Component
{
    use WithFileUploads;

    // Quản lý trạng thái trang (Trang 2: Danh sách, Trang 3: Chi tiết/Chỉnh sửa)
    public int $currentPage = 2; 

    public ?int $roadmap_id = null; 
    public ?int $section_id = null;
    public ?int $lesson_id = null;
    public ?string $title = null;
    public string $lesson_type = 'video';
    public ?string $video_url = null;
    
    /** @var mixed */
    public mixed $pdf_file = null; 
    
    public ?string $content = null;
    public ?string $current_pdf_path = null;
    public ?int $project_id = null;
    public int $sort_order = 0;

    /**
     * Khởi tạo và tự động truy vết ngược roadmap_id từ section_id
     */
    public function mount(?int $sectionId = null): void
    {
        $this->section_id = $sectionId;

        if ($sectionId) {
            $this->roadmap_id = RoadmapLesson::where('section_id', $sectionId)->value('roadmap_id');

            if (!$this->roadmap_id) {
                $this->roadmap_id = DB::table('roadmap_sections')->where('id', $sectionId)->value('roadmap_id') 
                    ?? DB::table('sections')->where('id', $sectionId)->value('roadmap_id');
            }
        }
    }

    /**
     * Quy tắc xác thực dữ liệu đầu vào
     */
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'lesson_type' => 'required|in:video,text',
            'video_url' => $this->lesson_type === 'video' ? 'required|url' : 'nullable',
            // SỬA LỖI: Chỉ cho phép nullable nếu ĐÃ CÓ file PDF hiện tại trong hệ thống
            'pdf_file' => $this->lesson_type === 'text' 
                ? (($this->lesson_id && $this->current_pdf_path) ? 'nullable|file|mimes:pdf|max:51200' : 'required|file|mimes:pdf|max:51200') 
                : 'nullable',
            'project_id' => 'nullable',
            'sort_order' => 'required|integer|min:0',
        ];
    }

    /**
     * Custom thông báo lỗi Tiếng Việt
     */
    protected function messages(): array
    {
        return [
            'title.required' => 'Vui lòng điền tiêu đề bài học.',
            'video_url.required' => 'Đường dẫn Video không được để trống khi chọn dạng Video.',
            'video_url.url' => 'Đường dẫn Video không đúng định dạng URL.',
            'pdf_file.required' => 'Vui lòng chọn hoặc kéo thả tệp giáo trình PDF.',
            'pdf_file.mimes' => 'Hệ thống chỉ chấp nhận định dạng file tài liệu chuẩn .pdf',
            'pdf_file.max' => 'Kích thước file tài liệu vượt quá giới hạn cho phép (Tối đa 50MB).',
        ];
    }

    /**
     * Xử lý Lưu thông tin bài học
     */
    public function save()
    {
        $this->validate();

        if (!$this->roadmap_id) {
            $this->roadmap_id = 1; 
        }

        // SỬA LỖI GHI ĐÈ: Lấy đường dẫn file cũ làm gốc, nếu upload file mới thì mới thay thế
        $finalContent = $this->current_pdf_path;
        if ($this->lesson_type === 'text' && $this->pdf_file) {
            // Xóa file cũ nếu có để tránh rác server
            if ($this->current_pdf_path) {
                Storage::disk('public')->delete('learning/' . $this->current_pdf_path);
            }
            $path = $this->pdf_file->store('learning', 'public');
            $finalContent = basename($path); 
        }

        // SỬA LỖI SLUG: Giữ nguyên slug cũ nếu là cập nhật, tránh đổi số ngẫu nhiên liên tục
        $slug = $this->lesson_id 
            ? (RoadmapLesson::where('id', $this->lesson_id)->value('slug') ?? (Str::slug($this->title) . '-' . rand(1000, 9999)))
            : (Str::slug($this->title) . '-' . rand(1000, 9999));

        RoadmapLesson::updateOrCreate(
            ['id' => $this->lesson_id],
            [
                'roadmap_id'  => $this->roadmap_id, 
                'section_id'  => $this->section_id,
                'title'       => $this->title,
                'lesson_type' => $this->lesson_type,
                'project_id'  => $this->project_id ?: null,
                'sort_order'  => $this->sort_order,
                'slug'        => $slug,
                'content'     => $this->lesson_type === 'text' ? $finalContent : null,
                'video_url'   => $this->lesson_type === 'video' ? $this->video_url : null,
            ]
        );

        session()->flash('message', 'Cấu hình bài học thành công!');
        $this->resetForm(); // Tự động chuyển về trang 2
    }

    /**
     * Đổ dữ liệu vào Form và CHUYỂN SANG TRANG 3 (Kích hoạt khi bấm vào biểu tượng video)
     */
    public function edit(int $id): void
    {
        $lesson = RoadmapLesson::findOrFail($id);
        
        $this->lesson_id = (int) $lesson->id;
        $this->roadmap_id = $lesson->roadmap_id ? (int) $lesson->roadmap_id : $this->roadmap_id;
        $this->title = $lesson->title;
        $this->lesson_type = $lesson->lesson_type;
        $this->video_url = $lesson->video_url;
        $this->current_pdf_path = $lesson->content; 
        $this->project_id = $lesson->project_id ? (int) $lesson->project_id : null;
        $this->sort_order = (int) $lesson->sort_order;

        // Chuyển hướng giao diện sang Trang 3
        $this->currentPage = 3;
    }

    /**
     * Xóa bài học
     */
    public function deleteLesson(int $id): void
    {
        $lesson = RoadmapLesson::find($id);
        if ($lesson) {
            if ($lesson->lesson_type === 'text' && $lesson->content) {
                Storage::disk('public')->delete('learning/' . $lesson->content);
            }
            $lesson->delete();
            session()->flash('message', 'Đã xóa bài học thành công!');
            $this->resetForm();
        }
    }

    /**
     * Quay lại Trang 2 từ Trang 3 không lưu
     */
    public function backToPage2(): void
    {
        $this->resetForm();
    }

    /**
     * Làm sạch Form dữ liệu và đưa về Trang 2
     */
    public function resetForm(): void
    {
        $this->reset(['lesson_id', 'title', 'video_url', 'pdf_file', 'current_pdf_path', 'project_id', 'sort_order', 'content']);
        $this->lesson_type = 'video';
        $this->currentPage = 2; // Ép về trang danh sách
    }

    public function render()
    {
        $view = view('learning::manage.management-lesson', [
            'lessons' => $this->section_id 
                ? RoadmapLesson::where('section_id', $this->section_id)->orderBy('sort_order')->get()
                : collect(),
            'projects' => Project::all()
        ]);

        /** @var \Illuminate\View\View $view */
        return $view->extends('learning::layouts.master')->section('content');
    }
}