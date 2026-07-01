<?php

namespace Modules\Learning\Livewire\Manage;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

// Import các Model chuẩn của bạn
use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Document\Models\Document;
use Modules\Exam\Models\Exam;
use Modules\Learning\Models\Project; 

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
    
    // Loại bài học và nội dung
    public string $lesson_type = 'text'; // text, video, document, exam, project
    public ?string $content = null; // For text lessons (HTML from Quill)
    public ?string $video_url = null; // For video lessons
    
    // Foreign keys cho các tài nguyên liên kết
    public ?int $document_id = null;
    public ?int $exam_id = null;
    public ?int $project_id = null;
    
    // Fix form update bug - Force re-render key
    public int $formKey = 0;

    // Mảng chứa danh sách chương mục (Phục vụ select box trong form nếu cần)
    public Collection $sections;
    
    // Danh sách tài nguyên để chọn
    public Collection $documents;
    public Collection $exams;
    public Collection $projects;

    // Quy tắc kiểm tra dữ liệu đầu vào (Validation)
    protected function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'sort_order' => 'required|integer|min:1',
            'description' => 'nullable|string',
            // 'section_id' => 'nullable|integer',
            'section_id' => [
                'nullable',
                'integer',
                Rule::exists('roadmap_sections', 'id')
                    ->where('roadmap_id', $this->roadmap->id),
            ],
            'is_published' => 'required|in:0,1',
            
            // Validation cho loại bài học và nội dung
            'lesson_type' => 'required|in:text,video,document,exam,project',
            'content' => 'required_if:lesson_type,text|nullable|string',
            'video_url' => 'required_if:lesson_type,video|nullable|url',
            'document_id' => 'required_if:lesson_type,document|nullable|integer|exists:documents,id',
            'exam_id' => 'required_if:lesson_type,exam|nullable|integer|exists:exams,id',
            'project_id' => 'required_if:lesson_type,project|nullable|integer|exists:projects,id',
        ];
    }

    // Thông báo lỗi giao diện bằng tiếng Việt
    protected array $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề bài học.',
        'sort_order.required' => 'Vui lòng nhập thứ thứ tự hiển thị.',
        'lesson_type.required' => 'Vui lòng chọn loại bài học.',
        'content.required_if' => 'Vui lòng nhập nội dung bài học.',
        'video_url.required_if' => 'Vui lòng nhập link video YouTube.',
        'video_url.url' => 'Link video không hợp lệ.',
        'document_id.required_if' => 'Vui lòng chọn tài liệu.',
        'exam_id.required_if' => 'Vui lòng chọn bài kiểm tra.',
        'project_id.required_if' => 'Vui lòng chọn bài tập.',
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

        $this->sections = $this->roadmap
            ->sections()
            ->with('lessons')
            ->orderBy('sort_order')
            ->get();
        
        // Load tài nguyên để chọn
        $this->documents = Document::where('status', 'approved')
            ->orderBy('title')
            ->get(['id', 'title']);
        
        $this->exams = Exam::where('status', 'approved')
            ->orderBy('title')
            ->get(['id', 'title']);
        
        $this->projects = Project::orderBy('title')
            ->get(['id', 'title']);
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
        // 🔥 FIX: Đóng form trước để force Livewire re-render
        $this->isOpenForm = false;
        
        $this->resetValidation();
        $lesson = RoadmapLesson::with(['document', 'exam', 'project'])->findOrFail($id);
        
        $this->lessonId = $lesson->id;
        $this->title = $lesson->title;
        $this->description = $lesson->description; 
        $this->sort_order = $lesson->sort_order;
        $this->section_id = $lesson->section_id;
        $this->is_published = $lesson->is_published ? 1 : 0;
        
        // Load các trường mới
        $this->lesson_type = $lesson->lesson_type ?? 'text';
        $this->content = $lesson->content;
        $this->video_url = $lesson->video_url;
        $this->document_id = $lesson->document_id;
        $this->exam_id = $lesson->exam_id;
        $this->project_id = $lesson->project_id;
        
        // 🔥 FIX: Force re-render bằng cách thay đổi key
        $this->formKey = now()->timestamp;

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
        
        // Reset các trường mới
        $this->lesson_type = 'text';
        $this->content = null;
        $this->video_url = null;
        $this->document_id = null;
        $this->exam_id = null;
        $this->project_id = null;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title),
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'section_id' => $this->section_id,
            'is_published' => $this->is_published,
            'lesson_type' => $this->lesson_type,
            'content' => $this->content,
            'video_url' => $this->video_url,
            'document_id' => $this->document_id,
            'exam_id' => $this->exam_id,
            'project_id' => $this->project_id,
        ];

        if ($this->isEditMode) {
            // Thực hiện Cập nhật bài học
            $lesson = RoadmapLesson::findOrFail($this->lessonId);
            $lesson->update($data);
            session()->flash('message', '🎉 Cập nhật bài học thành công!');
        } else {
            // Thực hiện Thêm mới bài học
            $data['roadmap_id'] = $this->roadmap->id;
            RoadmapLesson::create($data);
            session()->flash('message', '✨ Thêm bài học mới thành công!');
        }

        $this->closeForm();
        
        // Reload sections để hiển thị dữ liệu mới nhất
        $this->sections = $this->roadmap
            ->sections()
            ->with('lessons')
            ->orderBy('sort_order')
            ->get();
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
        // $lessons = RoadmapLesson::where('roadmap_id', $this->roadmap->id)
        //     ->where('title', 'like', '%' . $this->search . '%')
        //     ->orderBy('sort_order', 'asc')
        //     ->get();

        $view = view('learning::manage.management-detail', [
            // 'lessons' => $lessons,
            'sections' => $this->sections,
        ]);

        /** @var mixed $view */
        return $view->extends('layouts.contributor')->section('content');
    }
}