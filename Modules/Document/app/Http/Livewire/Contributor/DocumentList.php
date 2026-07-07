<?php

namespace Modules\Document\Http\Livewire\Contributor;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Modules\Document\Models\Document;

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
        $user = Auth::user()
            ?? User::whereHas('roles', fn ($q) => $q->where('name', 'contributor'))->first()
            ?? User::first();

        return $user?->id;
    }

    public function deleteDocument($id)
    {
        $doc = Document::where('author_id', $this->getAuthorId())->find($id);
        if ($doc) {
            $doc->delete(); // Soft delete
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa tài liệu thành công.']);
        }
    }

    /**
     * Dismiss a rejected version (mark it as read / delete the rejected version entry)
     * For a new submission (v1) being rejected: dismiss sends the doc back to draft state.
     * For an edit version: the contributor just dismisses the warning — original is still live.
     */
    public function dismissRejectedVersion($documentId)
    {
        $doc = Document::where('author_id', $this->getAuthorId())->find($documentId);
        if (! $doc) {
            return;
        }

        $rejectedVersion = $doc->rejectedVersion;
        if ($rejectedVersion) {
            if ($rejectedVersion->version_number === 1) {
                // First submission rejected – soft-delete the entire document for the contributor
                $doc->delete();
            } else {
                // Edit version rejected – just delete that rejected version record
                $rejectedVersion->delete();
            }
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa cảnh báo.']);
    }

    public function cancelUpdate($documentId)
    {
        $doc = Document::where('author_id', $this->getAuthorId())->find($documentId);
        if (! $doc) {
            return;
        }

        $pendingVersion = $doc->pendingVersion;
        if ($pendingVersion) {
            $pendingVersion->delete();
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã hủy yêu cầu cập nhật.']);
    }

    public function render()
    {
        $authorId = $this->getAuthorId();
        $categories = Category::where('is_active', true)->get();

        $query = Document::where('author_id', $authorId)->withTrashed();

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('versions', function ($vq) {
                    $vq->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('short_description', 'like', '%'.$this->search.'%');
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
            $query->whereHas('versions', function ($q) {
                $q->where('category_id', $this->categoryFilter);
            });
        }

        $documents = $query
            ->with([
                'product',
                'pendingVersion.category',
                'pendingVersion.reviewedByUser',
                'rejectedVersion.category',
                'rejectedVersion.reviewedByUser',
                'currentVersion.category',
                'currentVersion.reviewedByUser',
            ])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // Stats
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
            'breadcrumb' => new HtmlString('<span class="mx-2">/</span> Contributor <span class="mx-2">/</span> Tài liệu'),
        ]);
    }
}
