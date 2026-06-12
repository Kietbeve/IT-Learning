<?php

namespace Modules\Document\Http\Livewire\Contributor;

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

    private function getAuthorId()
    {
        $user = Auth::user() ?? \Modules\Auth\Models\User::whereHas('roles', function($q) {
            $q->where('name', 'contributor');
        })->first() ?? \Modules\Auth\Models\User::first();
        return $user?->id;
    }

    public function toggleVisibility($id)
    {
        $doc = Document::where('author_id', $this->getAuthorId())->find($id);
        if (!$doc) return;

        $newVisibility = $doc->visibility === 'public' ? 'private' : 'public';
        $doc->update(['visibility' => $newVisibility]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Cập nhật chế độ hiển thị thành công.']);
    }

    public function deleteDocument($id)
    {
        $doc = Document::where('author_id', $this->getAuthorId())->find($id);
        if ($doc) {
            $doc->delete(); // Soft delete
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa tài liệu thành công (Xóa mềm).']);
        }
    }

    public function render()
    {
        $authorId = $this->getAuthorId();
        $categories = Category::where('is_active', true)->get();

        // Query only contributor's own documents
        $query = Document::where('author_id', $authorId)->withTrashed();

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('short_description', 'like', '%' . $this->search . '%');
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

        $documents = $query->with(['category', 'product', 'reviewer'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // Counts for overall stats (restricted to this author)
        $totalCount = Document::where('author_id', $authorId)->withTrashed()->count();
        $approvedCount = Document::where('author_id', $authorId)->where('status', 'approved')->count();
        $pendingCount = Document::where('author_id', $authorId)->where('status', 'pending')->count();
        $totalDownloads = Document::where('author_id', $authorId)->sum('download_count');
        $totalViews = Document::where('author_id', $authorId)->sum('view_count');

        return view('document::livewire.contributor.document-list', [
            'documents' => $documents,
            'categories' => $categories,
            'totalCount' => $totalCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'totalDownloads' => $totalDownloads,
            'totalViews' => $totalViews,
        ])->layout('layouts.contributor', [
            'pageTitle' => 'Quản lý tài liệu của tôi',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu')
        ]);
    }
}
