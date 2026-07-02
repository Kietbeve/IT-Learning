<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentVersion;
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

    // Tab navigation
    public $activeTab = 'pending'; // pending | approved | rejected | all

    // Rejection modal state
    public $selectedDocumentId = null;
    public $rejectionReason = '';

    protected $queryString = [
        'search'         => ['except' => ''],
        'statusFilter'   => ['except' => 'all'],
        'categoryFilter' => ['except' => 'all'],
        'activeTab'      => ['except' => 'pending'],
    ];

    public function updatingSearch()        { $this->resetPage(); }
    public function updatingStatusFilter()  { $this->resetPage(); }
    public function updatingCategoryFilter(){ $this->resetPage(); }

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
            $this->sortField     = $field;
            $this->sortDirection = 'desc';
        }
    }

    public function toggleVisibility($id)
    {
        $doc = Document::find($id);
        if (!$doc) return;

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
        $doc = Document::find($id);
        if ($doc) {
            $doc->delete(); // Soft delete
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
        $doc = Document::with(['pendingVersion', 'currentVersion', 'product'])->find($id);
        if (!$doc) return;

        $pendingVersion = $doc->pendingVersion;

        if (!$pendingVersion) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Không tìm thấy phiên bản đang chờ duyệt.']);
            return;
        }

        if ($pendingVersion->watermark_status !== 'success') {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'File chưa được xử lý xong (watermark đang pending). Vui lòng thử lại sau.']);
            return;
        }

        // Mark the pending version as approved
        $pendingVersion->update([
            'status'      => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        // Merge version data into the main document record (only identity fields)
        $doc->update([
            'status'             => 'approved',
            'current_version_id' => $pendingVersion->id,
        ]);

        // Handle product based on version price
        if ($pendingVersion->price > 0) {
            \Modules\Payment\Models\Product::updateOrCreate(
                ['document_id' => $doc->id],
                ['name' => $pendingVersion->title, 'price' => $pendingVersion->price, 'is_active' => true]
            );
        } else {
            \Modules\Payment\Models\Product::where('document_id', $doc->id)->delete();
        }

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Phê duyệt tài liệu thành công.']);
    }

    public function openRejectionModal($id)
    {
        $this->selectedDocumentId = $id;
        $this->rejectionReason    = '';
        $this->dispatch('open-modal', 'reject-modal');
    }

    public function confirmRejection()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500',
        ], [
            'rejectionReason.min' => 'Lý do từ chối phải có ít nhất 10 ký tự.',
        ]);

        $doc = Document::with(['pendingVersion'])->find($this->selectedDocumentId);
        if (!$doc) {
            $this->selectedDocumentId = null;
            $this->rejectionReason    = '';
            return;
        }

        $pendingVersion = $doc->pendingVersion;

        if ($pendingVersion) {
            // Reject the pending version
            $pendingVersion->update([
                'status'          => 'rejected',
                'rejected_reason' => $this->rejectionReason,
                'reviewed_by'     => Auth::id(),
                'reviewed_at'     => now(),
            ]);

            // If this is Version 1 (first submission), also reject the document itself
            if ($pendingVersion->version_number === 1 && $doc->status === 'pending') {
                $doc->update([
                    'status' => 'rejected',
                ]);
            }
            // If it's an edit version (v > 1), keep the document as approved – just the new version is rejected.
        } else {
            // No pending version found - fallback: reject document directly
            $doc->update(['status' => 'rejected']);
        }

        $this->dispatch('close-modal', 'reject-modal');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Từ chối tài liệu thành công.']);

        $this->selectedDocumentId = null;
        $this->rejectionReason    = '';
    }

    public function render()
    {
        $categories = Category::where('is_active', true)->get();

        // Base query
        // watermark_status và các trường nội dung khác đã chuyển sang document_versions.
        // Đối với tài liệu đã approved, ta kiểm tra trên currentVersion hoặc latestVersion.
        $query = Document::query();

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('versions', function ($vq) {
                    $vq->where('title', 'like', '%' . $this->search . '%')
                       ->orWhere('short_description', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('author', fn($aq) => $aq->where('name', 'like', '%' . $this->search . '%'));
            });
        }

        // Filter by activeTab
        if ($this->activeTab === 'pending') {
            $query->where(function($q) {
                $q->where('status', 'pending')
                  ->orWhereHas('pendingVersion', fn($sq) => $sq->where('status', 'pending'));
            });
        } elseif ($this->activeTab === 'approved') {
            $query->where('status', 'approved');
        } elseif ($this->activeTab === 'rejected') {
            $query->where('status', 'rejected');
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
                'latestVersion.reviewedByUser'
            ])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // Annotate each document for badge display
        foreach ($documents as $doc) {
            $pendingV = $doc->pendingVersion;
            $currentV = $doc->currentVersion;

            if ($pendingV) {
                if ($pendingV->version_number > 1) {
                    // Update request (editing live document)
                    $doc->badge_type = 'update';
                    $doc->original_version = $currentV; // For displaying original document info
                    
                    // Check if this update was rejected before (has rejected_reason while pending)
                    if ($pendingV->rejected_reason) {
                        $doc->was_rejected = true;
                        $doc->rejection_info = $pendingV->rejected_reason;
                    }
                } elseif ($pendingV->version_number === 1) {
                    // Version 1: Check if previously rejected (has rejected_reason while pending)
                    if ($pendingV->rejected_reason) {
                        $doc->badge_type = 'resubmit';
                        $doc->rejection_info = $pendingV->rejected_reason;
                    } else {
                        $doc->badge_type = 'new';
                    }
                }
            } else {
                $doc->badge_type = null; // No pending version
            }

            $doc->pending_version_data = $pendingV;
        }

        // Counts for tabs
        $totalCount    = Document::count();
        $pendingCount  = Document::where(function ($q) {
            $q->where('status', 'pending')
              ->orWhereHas('pendingVersion', fn($sq) => $sq->where('status', 'pending'));
        })->count();
        $approvedCount = Document::where('status', 'approved')->count();
        $rejectedCount = Document::where('status', 'rejected')->count();
        $totalDownloads = Document::sum('download_count');

        return view('document::livewire.admin.document-list', [
            'documents'      => $documents,
            'categories'     => $categories,
            'totalCount'     => $totalCount,
            'approvedCount'  => $approvedCount,
            'pendingCount'   => $pendingCount,
            'rejectedCount'  => $rejectedCount,
            'totalDownloads' => $totalDownloads,
        ])->layout('layouts.admin', [
            'pageTitle'  => 'Quản lý tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Tài liệu <span class="mx-2">/</span> Danh sách'),
        ]);
    }
}
