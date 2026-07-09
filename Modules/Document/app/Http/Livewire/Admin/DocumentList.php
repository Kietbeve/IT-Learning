<?php

namespace Modules\Document\Http\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Events\DocumentApproved;
use Modules\Document\Events\DocumentDeletedByAdmin;
use Modules\Document\Events\DocumentRejected;
use Modules\Document\Services\DocumentApprovalService;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentVersion;
use Modules\Payment\Models\Product;

class DocumentList extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = 'all';

    public $categoryFilter = 'all';

    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    // Tab navigation
    public $activeTab = 'pending'; // pending | approved | rejected | unpublished | deleted | all

    public $selectedDocumentId = null;

    public $rejectionReason = '';

    public bool $showRejectionModal = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'categoryFilter' => ['except' => 'all'],
        'activeTab' => ['except' => 'pending'],
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

    public function setTab($tab)
    {
        $this->activeTab = $tab;
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
        if (! $doc) {
            return;
        }

        $newVisibility = $doc->visibility === 'public' ? 'private' : 'public';
        $doc->update(['visibility' => $newVisibility]);

        // Also update current version
        if ($doc->currentVersion) {
            $doc->currentVersion->update(['visibility' => $newVisibility]);
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Cập nhật chế độ hiển thị thành công.']);
    }

    public function deleteDocument($id)
    {
        $doc = Document::with('author')->find($id);
        if ($doc) {
            $hasPurchases = \Modules\Payment\Models\OrderItem::where('document_id', $id)
                ->whereHas('order', function ($q) {
                    $q->where('payment_status', 'paid');
                })->exists();

            if ($hasPurchases) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Không thể xóa vì tài liệu này đã có người mua.']);
                return;
            }

            $author = $doc->author;
            $title = $doc->title;
            $doc->delete(); // Soft delete

            // Thông báo cho Contributor
            if ($author) {
                event(new DocumentDeletedByAdmin($author, $title));
            }

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã xóa tài liệu thành công (Xóa mềm).']);
        }
    }

    /**
     * Approve a document or a pending version.
     * - If the document has only version 1 (first submission): mark doc as approved,
     *   set current_version_id, update doc fields.
     * - If the document has a pending version > 1 (edit submission): merge version
     *   data into the document and update current_version_id.
     */
    public function approve($id)
    {
        try {
            app(\Modules\Document\Services\DocumentApprovalService::class)->approve($id);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Phê duyệt tài liệu thành công.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function openRejectionModal($id)
    {
        $this->selectedDocumentId = $id;
        $this->rejectionReason = '';
        $this->showRejectionModal = true;
    }

    public function confirmRejection()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ], [
            'rejectionReason.min' => 'Lý do từ chối phải có ít nhất 10 ký tự.',
        ]);

        try {
            app(\Modules\Document\Services\DocumentApprovalService::class)->reject($this->selectedDocumentId, $this->rejectionReason);
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Từ chối tài liệu thành công.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'message' => $e->getMessage()]);
        }

        $this->showRejectionModal = false;

        $this->selectedDocumentId = null;
        $this->rejectionReason = '';
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();

        // Base query
        // watermark_status và các trường nội dung khác đã chuyển sang document_versions.
        // Đối với tài liệu đã approved, ta kiểm tra trên currentVersion hoặc latestVersion.
        $query = Document::query();

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('versions', function ($vq) {
                    $vq->where('title', 'like', '%'.$this->search.'%')
                        ->orWhere('short_description', 'like', '%'.$this->search.'%');
                })
                    ->orWhereHas('author', fn ($aq) => $aq->where('name', 'like', '%'.$this->search.'%'));
            });
        }

        // Filter by activeTab
        if ($this->activeTab === 'pending') {
            $query->whereHas('pendingVersion', function ($sq) {
                $sq->where('watermark_status', '!=', 'pending');
            });
        } elseif ($this->activeTab === 'approved') {
            $query->where('status', 'approved');
        } elseif ($this->activeTab === 'rejected') {
            $query->where('status', 'rejected');
        } elseif ($this->activeTab === 'unpublished') {
            $query->where('status', 'unpublished');
        } elseif ($this->activeTab === 'deleted') {
            $query->onlyTrashed();
        }

        if ($this->categoryFilter !== 'all') {
            $query->whereHas('versions', function ($q) {
                $q->where('category_id', $this->categoryFilter);
            });
        }

        $documents = $query
            ->with([
                'author',
                'product',
                'pendingVersion.category',
                'pendingVersion.reviewedByUser',
                'rejectedVersion.category',
                'rejectedVersion.reviewedByUser',
                'currentVersion.category',
                'currentVersion.reviewedByUser',
                'latestVersion.category',
                'latestVersion.reviewedByUser',
            ])
            ->when($this->sortField === 'title', function ($q) {
                $q->orderBy(DocumentVersion::select('title')
                    ->whereColumn('document_versions.id', 'documents.current_version_id'),
                    $this->sortDirection);
            })
            ->when($this->sortField !== 'title', function ($q) {
                $q->orderBy($this->sortField, $this->sortDirection);
            })
            ->paginate(15);

        // Annotate each document for badge display
        foreach ($documents as $doc) {
            $pendingV = $doc->pendingVersion;
            $currentV = $doc->currentVersion;

            $doc->pending_version_data = $pendingV;

            if (!$pendingV) {
                $doc->badge_type = null;
                continue;
            }

            // badge_type:
            //   'new'    = first submission ever (version 1)
            //   'update' = any revision (whether doc is live or not)
            // live_version is only set when doc has an approved live version
            if ($pendingV->version_number === 1) {
                $doc->badge_type = 'new';
            } else {
                $doc->badge_type = 'update';
            }

            // Only attach live_version when there's actually an approved version
            if (!is_null($currentV)) {
                $doc->live_version = $currentV;
            }
        }

        // Counts for tabs
        $totalCount = Document::count();
        $pendingCount = Document::whereHas('pendingVersion', function ($sq) {
            $sq->where('watermark_status', '!=', 'pending');
        })->count();
        $approvedCount = Document::where('status', 'approved')->count();
        $rejectedCount = Document::where('status', 'rejected')->count();
        $unpublishedCount = Document::where('status', 'unpublished')->count();
        $totalDownloads = Document::sum('download_count');

        return view('document::livewire.admin.document-list', [
            'documents' => $documents,
            'categories' => $categories,
            'totalCount' => $totalCount,
            'approvedCount' => $approvedCount,
            'pendingCount' => $pendingCount,
            'rejectedCount' => $rejectedCount,
            'unpublishedCount' => $unpublishedCount,
            'totalDownloads' => $totalDownloads,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Quản lý tài liệu',
            'breadcrumb' => new HtmlString('<span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Danh sách'),
        ]);
    }
}
