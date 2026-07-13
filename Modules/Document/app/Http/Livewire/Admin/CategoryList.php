<?php

namespace Modules\Document\Http\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $sortField = 'sort_order';
    public $sortDirection = 'asc';

    // Form fields
    public $categoryId = null;
    public $name = '';
    public $slug = '';
    public $description = '';
    public $sort_order = 0;
    public $is_active = true;
    public $isFormOpen = false;
    public $confirmDeleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'nullable|string|max:255',
        'description' => 'nullable|string|max:1000',
        'sort_order' => 'required|integer|min:0',
        'is_active' => 'boolean',
    ];

    public function updatedName($value)
    {
        if (empty($this->slug) || $this->slug === Str::slug($this->name)) {
            $this->slug = Str::slug($value);
        }
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isFormOpen = true; // Chỉ cần dùng state này, @entangle sẽ lo phần còn lại
    }

    public function editCategory($id)
    {
        $this->resetForm();
        $category = Category::find($id);
        if ($category) {
            $this->categoryId = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
            $this->description = $category->description;
            $this->sort_order = $category->sort_order;
            $this->is_active = $category->is_active;

            $this->isFormOpen = true;
        }
    }

    public function resetForm()
    {
        $this->reset(['categoryId', 'name', 'slug', 'description', 'sort_order', 'is_active', 'isFormOpen']);
        $this->resetErrorBag();
    }

    public function saveCategory()
    {
        $this->validate();

        if (empty($this->slug)) {
            $this->slug = Str::slug($this->name);
        }

        $baseSlug = $this->slug;
        $count = 1;
        // Lưu ý: Đảm bảo Model App\Models\Category có use SoftDeletes nhé
        while (Category::withTrashed()
            ->where('slug', $this->slug)
            ->when($this->categoryId, fn($q) => $q->where('id', '!=', $this->categoryId))
            ->exists()
        ) {
            $this->slug = $baseSlug . '-' . $count++;
        }

        Category::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                'slug' => $this->slug,
                'description' => $this->description,
                'sort_order' => $this->sort_order,
                'is_active' => $this->is_active,
                'type' => 'document',
            ]
        );

        $message = $this->categoryId ? 'Cập nhật danh mục thành công.' : 'Thêm danh mục mới thành công.';
        
        // FIX: Truyền arguments trực tiếp để tương thích chuẩn với Livewire 3
        $this->dispatch('notify', type: 'success', message: $message);

        $this->resetForm();
    }

    public function toggleStatus($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->update(['is_active' => ! $category->is_active]);
            $this->dispatch('notify', type: 'success', message: 'Thay đổi trạng thái danh mục thành công.');
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if (!$this->confirmDeleteId) return;
        
        $category = Category::find($this->confirmDeleteId);
        if ($category) {
            $hasDocs = \Modules\Document\Models\DocumentVersion::where('category_id', $this->confirmDeleteId)->exists();
            $hasSubjects = \Modules\Document\Models\Subject::where('category_id', $this->confirmDeleteId)->exists();
            if ($hasDocs || $hasSubjects) {
                $this->dispatch('notify', type: 'error', message: 'Không thể xóa vì đang có môn học hoặc tài liệu thuộc danh mục.');
                $this->confirmDeleteId = null;
                return;
            }

            $category->delete();
            $this->dispatch('notify', type: 'success', message: 'Đã xóa danh mục thành công.');
        }
        $this->confirmDeleteId = null;
    }

    public function render()
    {
        $totalCount = Category::where('type', 'document')->count();
        $activeCount = Category::where('type', 'document')->where('is_active', true)->count();
        $inactiveCount = Category::where('type', 'document')->where('is_active', false)->count();

        $query = Category::where('type', 'document');

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('description', 'like', '%'.$this->search.'%')
                  ->orWhere('slug', 'like', '%'.$this->search.'%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $categories = $query->orderBy($this->sortField, $this->sortDirection)->paginate(15);

        return view('document::livewire.admin.category-list', [
            'categories' => $categories,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Quản lý danh mục tài liệu',
            'breadcrumb' => new HtmlString('<span class="mx-2">/</span> Admin <span class="mx-2">/</span> Danh mục'),
        ]);
    }
}