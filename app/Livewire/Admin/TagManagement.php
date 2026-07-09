<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Tag;
use WireUi\Traits\WireUiActions;

#[Layout('layouts.admin')]
class TagManagement extends Component
{
    use WireUiActions;

    use WithPagination;

    // Properties cho tìm kiếm và modal
    public $search = '';
    public $showModal = false;
    public $modalMode = 'create'; // 'create' hoặc 'edit'
    public $confirmDeleteId = null;
    

    
    // Properties cho form
    public $tagId = null;
    public $name = '';
    // public $slug = '';

    /**
     * Reset pagination khi tìm kiếm thay đổi
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Mở modal để tạo tag mới
     */
    public function openCreateModal()
    {
        $this->resetForm();
        $this->modalMode = 'create';
        $this->showModal = true;
    }

    /**
     * Mở modal để chỉnh sửa tag
     * 
     * @param int $id ID của tag cần chỉnh sửa
     */
    public function openEditModal($id)
    {
        $tag = Tag::findOrFail($id);
        
        $this->tagId = $tag->id;
        $this->name = $tag->name;
        // $this->slug = $tag->slug;
        
        $this->modalMode = 'edit';
        $this->showModal = true;
    }

    /**
     * Lưu tag (tạo mới hoặc cập nhật)
     * Hàm này xử lý cả thêm mới và cập nhật tag
     */
    public function save()
    {
        // Validate dữ liệu đầu vào
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . ($this->tagId ?? 'NULL'),
            // 'slug' => 'required|string|max:255|unique:tags,slug,' . ($this->tagId ?? 'NULL'),
        ], [
            'name.required' => 'Tên tag là bắt buộc',
            'name.unique' => 'Tên tag đã tồn tại',
            // 'slug.required' => 'Slug là bắt buộc',
            // 'slug.unique' => 'Slug đã tồn tại',
        ]);

        $validated['slug'] = Tag::generateUniqueSlug($validated['name']);

        if ($this->modalMode === 'create') {
            // Tạo mới tag
            Tag::create($validated);
            // session()->flash('success', 'Tạo tag mới thành công!');
              $this->notification()->success(
                title: 'Tạo tag mới thành công!',
                //description: $message
            );
        } else {
            // Cập nhật tag hiện có
            $tag = Tag::findOrFail($this->tagId);
            $tag->update($validated);
            // session()->flash('success', 'Cập nhật tag thành công!');
            $this->notification()->success(
                title: 'Cập nhật tag thành công!',
                //description: $message
            );
        }

        $this->closeModal();
        $this->resetPage();
    }

    /**
     * Mở modal xác nhận xóa
     * 
     * @param int $id ID của tag cần xóa
     */
    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
        $tag = Tag::findOrFail($id);
        $this->name=$tag->name;
    }

    /**
     * Xóa tag (soft delete)
     */
    public function delete()
    {
        $tag = Tag::withCount(['documents', 'exams', 'questions'])->findOrFail($this->confirmDeleteId);
        
        if ($tag->documents_count > 0 || $tag->exams_count > 0 || $tag->questions_count > 0) {
            $parts = [];
            if ($tag->documents_count > 0) $parts[] = "{$tag->documents_count} tài liệu";
            if ($tag->exams_count > 0) $parts[] = "{$tag->exams_count} đề thi";
            if ($tag->questions_count > 0) $parts[] = "{$tag->questions_count} câu hỏi";
            
            $this->notification()->error(
                title: 'Không thể xóa tag',
                description: 'Tag này đang được sử dụng trong ' . implode(', ', $parts) . '.'
            );
            $this->confirmDeleteId = null;
            return;
        }

        $tag->delete();
        
        $this->confirmDeleteId = null;
        // session()->flash('success', 'Xóa tag thành công!');
        $this->notification()->success(
            title: 'Xóa tag thành công!',
        );
        $this->resetPage();
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
        $this->tagId = null;
        $this->name = '';
        // $this->slug = '';
        $this->resetValidation();
    }

    /**
     * Tự động tạo slug từ name khi name thay đổi
     * 
     * @param string $value Giá trị mới của name
     */
    // public function updatedName($value)
    // {
    //     $this->slug = Str::slug($value);
    // }



    /**
     * Render component với danh sách tags
     * 
     * @return \Illuminate\View\View
     */
    public function render()
    {
        // Query tags với điều kiện tìm kiếm và phân trang
        $tags = Tag::query()
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('slug', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.tag-management', [
            'tags' => $tags,
            'pageTitle' => 'Quản lý Tags',
        ]);
    }
}
