<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class DocumentList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $categoryFilter = 'all';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'categoryFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function toggleVisibility($id)
    {
        $doc = Document::find($id);
        if (!$doc) return;

        $newVisibility = $doc->visibility === 'public' ? 'private' : 'public';
        $doc->update(['visibility' => $newVisibility]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Cập nhật chế độ hiển thị thành công.']);
    }

    public function deleteDocument($id)
    {
        $doc = Document::find($id);
        if ($doc) {
            $doc->delete(); // Soft delete
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa tài liệu thành công (Xóa mềm).']);
        }
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();

        // Query
        $query = Document::withTrashed() // Include soft deleted documents
            ->whereNull('parent_document_id'); // Only show root documents, not drafts

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('short_description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('author', function($aq) {
                      $aq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $this->statusFilter);
            }
        }

        if ($this->categoryFilter !== 'all') {
            $query->where('category_id', $this->categoryFilter);
        }

        $documents = $query->with(['author', 'category', 'product', 'reviewer', 'pendingDrafts'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // Counts for overall stats
        $totalCount = Document::withTrashed()->whereNull('parent_document_id')->count();
        $approvedCount = Document::where('status', 'approved')->whereNull('parent_document_id')->count();
        $pendingCount = Document::where('status', 'pending')->whereNull('parent_document_id')->count();
        $totalDownloads = Document::sum('download_count');

        return view('document::livewire.admin.document-list', [
            'documents' => $documents,
            'categories' => $categories,
            'totalCount' => $totalCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'totalDownloads' => $totalDownloads,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Quản lý tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Danh sách')
        ]);
    }
}
