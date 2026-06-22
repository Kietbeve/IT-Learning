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

    public function deleteDocument($id)
    {
        $doc = Document::where('author_id', $this->getAuthorId())->find($id);
        if ($doc) {
            $doc->delete(); // Soft delete
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa tài liệu thành công (Xóa mềm).']);
        }
    }

    public function dismissRejectedDraft($documentId)
    {
        Document::where('parent_document_id', $documentId)
            ->where('status', 'rejected')
            ->forceDelete();
        
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa cảnh báo.']);
    }

    public function dismissRejection($documentId, $type)
    {
        if ($type === 'draft') {
            // Delete rejected drafts
            Document::where('parent_document_id', $documentId)
                ->where('status', 'rejected')
                ->forceDelete();
            
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa cảnh báo bản nháp bị từ chối.']);
        } elseif ($type === 'direct') {
            // Soft delete the rejected document
            $doc = Document::where('author_id', $this->getAuthorId())
                ->where('id', $documentId)
                ->where('status', 'rejected')
                ->first();
            
            if ($doc) {
                $doc->delete(); // Soft delete
                $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã ẩn tài liệu bị từ chối.']);
            }
        }
    }

    public function render()
    {
        $authorId = $this->getAuthorId();
        $categories = Category::where('is_active', true)->get();

        // Query only contributor's own documents
        $query = Document::where('author_id', $authorId)->withTrashed();

        // Hide rejected drafts (show only original docs or non-rejected drafts)
        $query->where(function($q) {
            $q->whereNull('parent_document_id') // Always show original documents
              ->orWhere('status', '!=', 'rejected'); // Only show non-rejected drafts
        });

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

        $documents = $query->with(['category', 'product', 'reviewer', 'rejectedDrafts'])
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
