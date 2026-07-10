<?php

namespace Modules\Document\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Modules\Document\Models\Subject;
use App\Models\Category;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.admin')]
class SubjectManagement extends Component
{
    use WireUiActions;
    use WithPagination;

    // Properties cho tìm kiếm và modal
    public $search = '';
    public $showModal = false;
    public $modalMode = 'create'; // 'create' hoặc 'edit'
    public $confirmDeleteId = null;
    

    
    // Properties cho form
    public $subjectId = null;
    public $name = '';
    public $category_id = '';
    public $is_active = true;

    /**
     * Reset pagination khi tìm kiếm thay đổi
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Mở modal để tạo môn học mới
     */
    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->showModal = true;
    }

    /**
     * Mở modal để chỉnh sửa môn học
     * 
     * @param int $id ID của môn học cần chỉnh sửa
     */
    public function openEditModal($id)
    {
        $subject = Subject::findOrFail($id);
        
        $this->subjectId = $subject->id;
        $this->name = $subject->name;
        $this->category_id = $subject->category_id;
        $this->is_active = $subject->is_active;
        
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    /**
     * Lưu môn học (tạo mới hoặc cập nhật)
     * Hàm này xử lý cả thêm mới và cập nhật môn học
     */
    public function save()
    {
        // Validate dữ liệu đầu vào
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . ($this->subjectId ?? 'NULL'),
            'category_id' => 'required|exists:categories,id',
            'is_active' => 'boolean',
        ], [
            'name.required' => 'Tên môn học là bắt buộc',
            'name.unique' => 'Tên môn học đã tồn tại',
            'category_id.required' => 'Danh mục là bắt buộc',
            'category_id.exists' => 'Danh mục không tồn tại',
        ]);

        // Tự động tạo slug từ tên môn học
        $validated['slug'] = Subject::generateUniqueSlug($validated['name']);

        if ($this->modalMode === 'create') {
            // Tạo mới môn học
            Subject::create($validated);
            $this->notification()->success(
                title: 'Tạo môn học mới thành công!',
            );
        } else {
            // Cập nhật môn học hiện có
            $subject = Subject::findOrFail($this->subjectId);
            $subject->update($validated);
            $this->notification()->success(
                title: 'Cập nhật môn học thành công!',
            );
        }

        $this->closeModal();
        $this->resetPage();
    }

    /**
     * Mở modal xác nhận xóa
     * 
     * @param int $id ID của môn học cần xóa
     */
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
        $subject = Subject::findOrFail($id);
        $this->name = $subject->name;
    }

    /**
     * Xóa môn học
     */
    public function delete()
    {
        $subject = Subject::withCount(['documents'])
            ->findOrFail($this->confirmDeleteId);

        if ($subject->documents_count > 0) {
            $this->notification()->error(
                title: 'Không thể xóa môn học',
                description: 'Có '. $subject->documents_count . ' tài liệu trong môn học này.'
            );
            $this->resetPage();
            $this->confirmDeleteId = null;
            return;
        }

        $subject->delete();
        
        $this->confirmDeleteId = null;
        $this->notification()->success(
            title: 'Xóa môn học thành công!',
        );
        $this->resetPage();
    }

    /**
     * Chuyển đổi trạng thái is_active của môn học
     * 
     * @param int $id ID của môn học cần chuyển đổi trạng thái
     */
    public function toggleActive($id)
    {
        $subject = Subject::findOrFail($id);
        $subject->is_active = !$subject->is_active;
        $subject->save();

        $status = $subject->is_active ? 'kích hoạt' : 'vô hiệu hóa';
        $this->notification()->success(
            title: "Đã {$status} môn học thành công!",
        );
    }

    /**
     * Đóng modal và reset form
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Reset các trường form về giá trị mặc định
     */
    private function resetForm()
    {
        $this->subjectId = null;
        $this->name = '';
        $this->category_id = '';
        $this->is_active = true;
        $this->resetValidation();
    }



    /**
     * Render component với danh sách môn học
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Query môn học với điều kiện tìm kiếm và phân trang
        $subjects = Subject::query()
            ->with('category') // Load relationship category để hiển thị tên danh mục
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', function($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->latest()
            ->paginate(10);

        // Lấy tất cả categories đang active cho dropdown
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('document::livewire.admin.subject-management', [
            'subjects' => $subjects,
            'categories' => $categories,
            'pageTitle' => 'Quản lý Môn học',
        ]);
    }
}
