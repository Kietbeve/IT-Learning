<?php

namespace Modules\Learning\Livewire\Manage;

use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use Modules\Learning\Models\Roadmap;
use Modules\Learning\Models\RoadmapLesson;
use Modules\Learning\Models\RoadmapSection;
use Modules\Document\Models\DocumentVersion;
use Modules\Exam\Models\Exam;
use Modules\Learning\Models\Project; 

class ManagementDetail extends Component
{
    public ?Roadmap $roadmap = null;
    public string $search = '';
    public bool $isOpenForm = false;
    public bool $isEditMode = false;

    public ?int $lessonId = null;
    public string $title = '';
    public ?string $description = null; 
    public $sort_order = 1; 
    public $section_id = null; // Bỏ ?int để tránh lỗi ép kiểu của PHP 8
    public string $new_section_title = ''; 
    public int $is_published = 1;
    
    public string $lesson_type = 'text'; 
    public ?string $content = null; 
    public ?string $video_url = null; 
    
    public ?int $document_id = null;
    public ?int $exam_id = null;
    public ?int $project_id = null;
    
    public int $formKey = 0;

    public Collection $sections;
    public Collection $documents;
    public Collection $exams;
    public Collection $projects;
    public Collection $lessons;

    protected function rules(): array
    {
        // Các luật kiểm tra chung cho mọi bài học
        $rules = [
            'title' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:1',
            'description' => 'nullable|string',
            'section_id' => 'nullable', 
            'new_section_title' => 'nullable|string|max:255',
            'is_published' => 'required|in:0,1',
            'lesson_type' => 'required|in:text,video,document,exam,project',
        ];

        // Tùy theo loại bài học đang chọn mà bắt buộc ô đó, các ô khác sẽ ĐƯỢC THẢ TỰ DO hoàn toàn
        if ($this->lesson_type === 'text') {
            $rules['content'] = 'required|string';
        } elseif ($this->lesson_type === 'video') {
            $rules['video_url'] = 'required|url';
        } elseif ($this->lesson_type === 'document') {
            $rules['document_id'] = 'required|integer';
        } elseif ($this->lesson_type === 'exam') {
            $rules['exam_id'] = 'required|integer';
        } elseif ($this->lesson_type === 'project') {
            $rules['project_id'] = 'required|integer';
        }

        return $rules;
    }

    protected array $messages = [
        'title.required' => 'Vui lòng nhập tiêu đề bài học.',
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
        $roadmapId = request()->query('id') ?? request()->route('id');
        
        if (!$roadmapId) {
            redirect()->to('/manage/roadmap');
            return;
        }
        
        $this->roadmap = Roadmap::findOrFail($roadmapId);
        $this->loadData();
    }

    private function loadData(): void
    {
        if ($this->roadmap) {
        $this->roadmap->refresh();
        }
        $this->sections = $this->roadmap
            ->sections()
            ->with('lessons')
            ->orderBy('sort_order')
            ->get();

        $this->lessons = \Modules\Learning\Models\RoadmapLesson::where('roadmap_id', $this->roadmap->id)
        ->with('section') // Eager load mối quan hệ chương để hiển thị tên chương
        ->orderBy('sort_order')
        ->get();
        
     
    $this->documents = DocumentVersion::where('status', 'approved')
    ->orderBy('title')
    ->select('document_id as id', 'title') 
    ->get();
        $this->exams = Exam::where('status', 'approved')->orderBy('title')->get(['id', 'title']);
        $this->projects = Project::orderBy('title')->get(['id', 'title']);
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
        $this->isOpenForm = false;
        
        $this->resetValidation();
        $lesson = RoadmapLesson::with(['document', 'exam', 'project'])->findOrFail($id);
        
        $this->lessonId = $lesson->id;
        $this->title = $lesson->title;
        $this->description = $lesson->description; 
        $this->sort_order = $lesson->sort_order;
        $this->section_id = $lesson->section_id;
        $this->new_section_title = '';
        $this->is_published = $lesson->is_published ? 1 : 0;
        
        $this->lesson_type = $lesson->lesson_type ?? 'text';
        $this->content = $lesson->content;
        $this->video_url = $lesson->video_url;
        $this->document_id = $lesson->document_id;
        $this->exam_id = $lesson->exam_id;
        $this->project_id = $lesson->project_id;
        
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
        $this->new_section_title = '';
        $this->is_published = 1;
        
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

        $finalSectionId = $this->section_id;

        // Xử lý tạo chương mới
        if (empty($this->section_id) && !empty(trim($this->new_section_title))) {
            $maxSortOrder = RoadmapSection::where('roadmap_id', $this->roadmap->id)->max('sort_order') ?? 0;
            
            $newSection = RoadmapSection::create([
                'roadmap_id' => $this->roadmap->id,
                'title' => trim($this->new_section_title), // Trả lại chuẩn title theo CSDL của bạn
                'sort_order' => $maxSortOrder + 1,
            ]);
            
            $finalSectionId = $newSection->id;
        }

        $data = [
            'title' => $this->title,
            'slug' => Str::slug($this->title) . '-' . rand(100, 999), // Tránh lỗi trùng slug nếu có
            
            'sort_order' => $this->sort_order ?: 1, // Mặc định là 1 nếu null
            'section_id' => empty($finalSectionId) ? null : $finalSectionId,
            'is_published' => $this->is_published,
            'lesson_type' => $this->lesson_type,
            'content' => $this->content,
            'video_url' => $this->video_url,
            'document_id' => $this->document_id,
            'exam_id' => $this->exam_id,
            'project_id' => $this->project_id,
        ];

        if ($this->isEditMode) {
            $lesson = RoadmapLesson::findOrFail($this->lessonId);
            $lesson->update($data);
            session()->flash('message', '🎉 Cập nhật bài học thành công!');
        } else {
            $data['roadmap_id'] = $this->roadmap->id;
            RoadmapLesson::create($data);
            session()->flash('message', '✨ Thêm bài học mới thành công!');
        }

        $this->closeForm();
        $this->loadData(); // Cập nhật lại danh sách ngay lập tức
    }

   public function deleteLesson(int $id): void
{
    // 1. Tìm bài học cần xóa
    $lesson = RoadmapLesson::findOrFail($id);
    
    // 2. Lưu lại ID của chương (section_id) trước khi xóa bài học này đi
    $sectionId = $lesson->section_id;

    // 3. Tiến hành xóa bài học
    $lesson->delete();

    // 4. Nếu bài học thuộc một chương nào đó, kiểm tra xem chương đó còn bài học nào không
    if ($sectionId) {
        $hasAnyLessons = RoadmapLesson::where('section_id', $sectionId)->exists();
        
        // Nếu không còn bất kỳ bài học nào trong chương này, xóa luôn chương
        if (!$hasAnyLessons) {
            RoadmapSection::where('id', $sectionId)->delete();
        }
    }

    // 5. Thông báo và làm mới lại danh sách giao diện
    session()->flash('message', '🗑️ Đã xóa bài học và dọn dẹp chương trống thành công!');
    $this->loadData();
}

   public function render()
{
    // 1. Kéo trực tiếp dữ liệu mới nhất ở đây
    $sections = \Modules\Learning\Models\RoadmapSection::where('roadmap_id', $this->roadmap->id)
        ->orderBy('sort_order')
        ->get();

    $lessons = \Modules\Learning\Models\RoadmapLesson::where('roadmap_id', $this->roadmap->id)
        ->with('section')
        ->orderBy('sort_order')
        ->get();

    // 2. Truyền thẳng các biến cục bộ này ra ngoài View
    $view = view('learning::manage.management-detail', [
        'sections' => $sections,
        'lessons'  => $lessons,
    ]);

    /** @var mixed $view */
    return $view->extends('layouts.contributor')->section('content');
}
}