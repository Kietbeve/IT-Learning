<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Illuminate\Support\Facades\Auth;

class DocumentModeration extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    // Rejection state
    public $selectedDocumentId = null;
    public $rejectionReason = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
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

    public function approve($id)
    {
        $doc = Document::find($id);
        if (!$doc) return;

        if ($doc->parent_document_id) {
            // This is a draft - merge into original document
            $original = Document::find($doc->parent_document_id);
            
            if (!$original) {
                $this->dispatch('notify', ['type' => 'error', 'message' => 'Không tìm thấy tài liệu gốc!']);
                return;
            }

            // Merge all changes from draft to original
            $original->update([
                'title' => $doc->title,
                // 'slug' => keep original slug unchanged
                'category_id' => $doc->category_id,
                'description' => $doc->description,
                'short_description' => $doc->short_description,
                'thumbnail' => $doc->thumbnail,
                'file_original_path' => $doc->file_original_path,
                'file_watermarked_path' => $doc->file_watermarked_path,
                'preview_file_path' => $doc->preview_file_path,
                'file_type' => $doc->file_type,
                'file_size' => $doc->file_size,
                'visibility' => $doc->visibility,
                'is_downloadable' => $doc->is_downloadable,
                'watermark_status' => $doc->watermark_status,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'published_at' => now(),
            ]);

            // Handle product (copy from draft to original)
            $draftProduct = $doc->product;
            if ($draftProduct) {
                \Modules\Payment\Models\Product::updateOrCreate(
                    ['document_id' => $original->id],
                    [
                        'name' => $draftProduct->name,
                        'price' => $draftProduct->price,
                        'is_active' => $draftProduct->is_active,
                    ]
                );
            } else {
                \Modules\Payment\Models\Product::where('document_id', $original->id)->delete();
            }

            // Delete ALL other drafts with same parent (clean up rejected/pending drafts)
            Document::where('parent_document_id', $original->id)
                ->where('id', '!=', $doc->id)
                ->forceDelete();

            // Delete the approved draft permanently (already merged into original)
            $doc->forceDelete();

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã merge draft vào tài liệu #' . $original->id]);
        } else {
            // Normal document - just approve
            $doc->update([
                'status' => 'approved',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'published_at' => now(),
            ]);

            $this->dispatch('notify', ['type' => 'success', 'message' => 'Phê duyệt tài liệu thành công.']);
        }
    }

    public function openRejectionModal($id)
    {
        $this->selectedDocumentId = $id;
        $this->rejectionReason = '';
        $this->dispatch('open-modal', 'reject-modal');
    }

    public function confirmRejection()
    {
        $this->validate([
            'rejectionReason' => 'required|string|min:10|max:500'
        ], [
            'rejectionReason.min' => 'Lý do từ chối phải có tối thiểu 10 ký tự.'
        ]);

        $doc = Document::find($this->selectedDocumentId);
        if ($doc) {
            if ($doc->parent_document_id) {
                // This is a draft - reject it (keep original, contributor can see reason)
                $doc->update([
                    'status' => 'rejected',
                    'rejected_reason' => $this->rejectionReason,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);
                
                $this->dispatch('close-modal', 'reject-modal');
                $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã từ chối bản chỉnh sửa. Tài liệu gốc vẫn còn.']);
            } else {
                // Normal document - reject it
                $doc->update([
                    'status' => 'rejected',
                    'rejected_reason' => $this->rejectionReason,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);

                $this->dispatch('close-modal', 'reject-modal');
                $this->dispatch('notify', ['type' => 'success', 'message' => 'Từ chối phê duyệt tài liệu thành công.']);
            }
        }

        $this->selectedDocumentId = null;
        $this->rejectionReason = '';
    }

    public function render()
    {
        // Counts for statistics row
        $pendingCount = Document::where('status', 'pending')->count();
        $approvedTodayCount = Document::where('status', 'approved')
            ->where('reviewed_at', '>=', now()->startOfDay())
            ->count();
        $rejectedTodayCount = Document::where('status', 'rejected')
            ->where('reviewed_at', '>=', now()->startOfDay())
            ->count();

        // Main moderation query — only show documents with successful watermark
        $query = Document::where('status', 'pending')
            ->where('watermark_status', 'success');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('short_description', 'like', '%' . $this->search . '%')
                  ->orWhereHas('author', function($aq) {
                      $aq->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $documents = $query->with(['author', 'category'])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        return view('document::livewire.admin.document-moderation', [
            'documents' => $documents,
            'pendingCount' => $pendingCount,
            'approvedTodayCount' => $approvedTodayCount,
            'rejectedTodayCount' => $rejectedTodayCount
        ])->layout('layouts.admin', [
            'pageTitle' => 'Hàng đợi kiểm duyệt tài liệu',
            'breadcrumb' => new \Illuminate\Support\HtmlString('<span class="mx-2">/</span> Kiểm duyệt <span class="mx-2">/</span> Hàng đợi')
        ]);
    }
}
