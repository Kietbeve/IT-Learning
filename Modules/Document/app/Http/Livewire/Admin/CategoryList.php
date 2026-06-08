<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use Illuminate\Support\Str;

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

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'slug' => 'required|string|max:255',
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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

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
        $this->isFormOpen = true;
        $this->dispatch('open-modal', 'category-form-modal');
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
            $this->dispatch('open-modal', 'category-form-modal');
        }
    }

    public function resetForm()
    {
        $this->categoryId = null;
        $this->name = '';
        $this->slug = '';
        $this->description = '';
        $this->sort_order = 0;
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function saveCategory()
    {
        $this->validate();

        // Enforce uniqueness of slug within document type categories
        $duplicateCheck = Category::where('slug', $this->slug)
            ->where('type', 'document');
        if ($this->categoryId) {
            $duplicateCheck->where('id', '!=', $this->categoryId);
        }
        if ($duplicateCheck->exists()) {
            $this->addError('slug', 'Đường dẫn (Slug) này đã tồn tại.');
            return;
        }

        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
            'type' => 'document', // Always document type here
        ];

        if ($this->categoryId) {
            $category = Category::find($this->categoryId);
            if ($category) {
                $category->update($data);
                $this->dispatch('notify', ['type' => 'success', 'message' => 'Cập nhật danh mục thành công.']);
            }
        } else {
            Category::create($data);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Thêm danh mục mới thành công.']);
        }

        $this->isFormOpen = false;
        $this->dispatch('close-modal', 'category-form-modal');
        $this->resetForm();
    }

    public function toggleStatus($id)
    {
        $category = Category::find($id);
        if ($category) {
            $category->update([
                'is_active' => !$category->is_active
            ]);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Thay đổi trạng thái danh mục thành công.']);
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            // Check if there are any documents using this category
            $hasDocs = \Modules\Document\Models\Document::where('category_id', $id)->exists();
            if ($hasDocs) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Không thể xóa danh mục này vì đang có tài liệu thuộc danh mục.']);
                return;
            }

            $category->delete(); // Soft delete
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa danh mục thành công.']);
        }
    }

    public function render()
    {
        $totalCount = Category::where('type', 'document')->count();
        $activeCount = Category::where('type', 'document')->where('is_active', true)->count();
        $inactiveCount = Category::where('type', 'document')->where('is_active', false)->count();

        // Query
        $query = Category::where('type', 'document');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%')
                  ->orWhere('slug', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('is_active', $this->statusFilter === 'active');
        }

        $categories = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('document::livewire.admin.category-list', [
            'categories' => $categories,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'inactiveCount' => $inactiveCount,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Quản lý danh mục tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Admin <span class="mx-2">/</span> Danh mục')
        ]);
    }
}
