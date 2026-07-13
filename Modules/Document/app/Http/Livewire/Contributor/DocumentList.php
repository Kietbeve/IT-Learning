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

    public $showHistoryModal = false;

    public $submissionHistory = [];

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

    public $confirmDeleteId = null;

    public function confirmDelete($id)
    {
        $this->confirmDeleteId = $id;
    }

    public function delete()
    {
        if (!$this->confirmDeleteId) return;

        $doc = Document::where('author_id', $this->getAuthorId())->find($this->confirmDeleteId);
        if ($doc) {
            $hasPurchases = \Modules\Payment\Models\OrderItem::where('document_id', $this->confirmDeleteId)
                ->whereHas('order', function ($q) {
                    $q->where('payment_status', 'paid');
                })->exists();

            if ($hasPurchases) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Không thể xóa vì tài liệu này đã có người mua.']);
                $this->confirmDeleteId = null;
                return;
            }

            // Deactivate product if it exists
            \Modules\Payment\Models\Product::where('document_id', $doc->id)->update(['is_active' => false]);
            
            $doc->update(['deleted_by' => Auth::id()]);
            $doc->delete(); // Soft delete
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa tài liệu thành công.']);
        }
        $this->confirmDeleteId = null;
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
                // Keep the version data but clear the reason to dismiss the badge
                $rejectedVersion->update(['rejected_reason' => null]);
            } else {
                // Edit version rejected – safe to delete the rejected version record
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

    public function showHistory($documentId)
    {
        $doc = Document::where('author_id', $this->getAuthorId())
            ->withTrashed()
            ->with('deletedByUser')
            ->find($documentId);
        if (! $doc) {
            $this->submissionHistory = collect();
            $this->showHistoryModal = true;
            return;
        }

        // Load all versions with their submitter and reviewer
        $versions = \Modules\Document\Models\DocumentVersion::where('document_id', $doc->id)
            ->with(['submittedByUser', 'reviewedByUser'])
            ->orderBy('version_number', 'asc')
            ->get();

        // Build a timeline from the versions
        $timeline = collect();
        foreach ($versions as $ver) {
            $isAdminDirectEdit = $ver->rejected_reason === 'Quản trị viên đã trực tiếp chỉnh sửa tài liệu';

            $timeline->push((object) [
                'type' => 'submission',
                'version_number' => $ver->version_number,
                'timestamp' => $ver->submitted_at,
                'user' => $ver->submittedByUser,
                'status' => $isAdminDirectEdit ? 'approved' : null,
                'details' => $isAdminDirectEdit ? $ver->rejected_reason : null,
            ]);

            // Only push a separate review event if it's not a direct admin edit
            if ($ver->reviewed_at && !$isAdminDirectEdit) {
                $timeline->push((object) [
                    'type' => 'review',
                    'version_number' => $ver->version_number,
                    'timestamp' => $ver->reviewed_at,
                    'user' => $ver->reviewedByUser,
                    'status' => $ver->status,
                    'details' => $ver->rejected_reason,
                ]);
            }
        }

        if ($doc->trashed()) {
            $timeline->push((object) [
                'type' => 'deleted',
                'version_number' => null,
                'timestamp' => $doc->deleted_at,
                'user' => $doc->deletedByUser,
                'status' => null,
                'details' => 'Tài liệu đã được chuyển vào thùng rác (xóa mềm).',
            ]);
        }

        $this->submissionHistory = $timeline->sortBy('timestamp')->values();
        $this->showHistoryModal = true;
    }

    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->submissionHistory = [];
    }

    public function render()
    {
        $authorId = $this->getAuthorId();
        $categories = Category::where('is_active', true)->get();

        $query = Document::where('author_id', $authorId)->withTrashed();

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('versions', function ($vq) {
                    $vq->where('title', 'like', '%'.$this->search.'%');
                });
            });
        }

        if ($this->statusFilter !== 'all') {
            if ($this->statusFilter === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->whereNull('deleted_at')->where('status', $this->statusFilter);
            }
        }

        if ($this->categoryFilter !== 'all') {
            $query->whereHas('versions', function ($q) {
                $q->where('category_id', $this->categoryFilter);
            });
        }

        if ($this->sortField === 'title') {
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
                ->orderBy(
                    \Modules\Document\Models\DocumentVersion::select('title')
                        ->from('document_versions')
                        ->whereColumn('document_versions.id', 'documents.current_version_id')
                        ->limit(1),
                    $this->sortDirection
                )
                ->paginate(15);
        } else {
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
        }

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
