<?php

namespace Modules\Document\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\Document;
use Modules\Document\Models\DocumentRelationship;
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
    
    // History modal state
    public $showHistoryModal = false;
    public $historyDocumentId = null;
    public $submissionHistory = [];

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

            // Update DocumentRelationship: approve this draft's relationship, reject others
            DocumentRelationship::where('draft_document_id', $doc->id)
                ->update([
                    'status' => 'approved',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);

            // Reject other pending relationships for same parent
            DocumentRelationship::where('parent_document_id', $original->id)
                ->where('draft_document_id', '!=', $doc->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'rejected',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                    'rejected_reason' => 'Admin duyệt bản cập nhật khác',
                ]);

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

            // Update DocumentRelationship
            DocumentRelationship::where('draft_document_id', $doc->id)
                ->where('status', 'pending')
                ->update([
                    'status' => 'approved',
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
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
            'rejectionReason.min' => 'Lý do từ chối phải có ít nhất 10 ký tự.'
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

                // Update DocumentRelationship
                DocumentRelationship::where('draft_document_id', $doc->id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'rejected',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                        'rejected_reason' => $this->rejectionReason,
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

                // Update DocumentRelationship
                DocumentRelationship::where('draft_document_id', $doc->id)
                    ->where('status', 'pending')
                    ->update([
                        'status' => 'rejected',
                        'reviewed_by' => Auth::id(),
                        'reviewed_at' => now(),
                        'rejected_reason' => $this->rejectionReason,
                    ]);

                $this->dispatch('close-modal', 'reject-modal');
                $this->dispatch('notify', ['type' => 'success', 'message' => 'Từ chối phê duyệt tài liệu thành công.']);
            }
        }

        $this->selectedDocumentId = null;
        $this->rejectionReason = '';
    }
    
    public function showHistory($documentId)
    {
        $this->historyDocumentId = $documentId;
        
        // Load document to walk the chain
        $doc = Document::withTrashed()->find($documentId);
        if (!$doc) {
            $this->submissionHistory = collect();
            $this->showHistoryModal = true;
            return;
        }
        
        // Walk UP to find root document
        $root = $doc;
        while ($root->parent_document_id) {
            $parent = Document::withTrashed()->find($root->parent_document_id);
            if (!$parent) break;
            $root = $parent;
        }
        
        // Collect all document IDs in the chain: root + all its children (drafts)
        $documentIds = [$root->id];
        $children = Document::withTrashed()
            ->where('parent_document_id', $root->id)
            ->pluck('id')
            ->toArray();
        $documentIds = array_merge($documentIds, $children);
        
        // Query ALL relationships for documents in the chain
        $relationships = DocumentRelationship::whereIn('draft_document_id', $documentIds)
            ->with(['submittedByUser', 'reviewedByUser'])
            ->orderBy('submitted_at', 'asc')
            ->get();
        
        // FALLBACK: Build timeline from document chain if no relationships found
        if ($relationships->isEmpty()) {
            $root = Document::withTrashed()
                ->with(['author', 'reviewer', 'histories.author', 'histories.reviewer', 'parentDocument.author', 'parentDocument.reviewer'])
                ->find($root->id);
            
            $history = collect();
            $this->buildTimelineFromChain($root, $history);
            
            $relationships = $history->sortBy('submitted_at')->values();
        }
        
        // Transform relationships into separate timeline events
        $timeline = collect();
        foreach ($relationships as $rel) {
            // Event 1: Submission
            $timeline->push((object)[
                'type' => 'submission',
                'event_type' => $rel->relationship_type ?? 'new_submission',
                'timestamp' => $rel->submitted_at,
                'user' => $rel->submittedByUser ?? $rel->author ?? null,
                'status' => null,
                'details' => null,
            ]);
            
            // Event 2: Review (only if reviewed)
            if ($rel->reviewed_at) {
                $timeline->push((object)[
                    'type' => 'review',
                    'event_type' => $rel->status ?? 'pending',
                    'timestamp' => $rel->reviewed_at,
                    'user' => $rel->reviewedByUser ?? $rel->reviewer ?? null,
                    'status' => $rel->status,
                    'details' => $rel->rejected_reason ?? null,
                ]);
            }
        }
        
        // Sort all events by timestamp
        $this->submissionHistory = $timeline->sortBy('timestamp')->values();
        $this->showHistoryModal = true;
    }
    
    private function buildTimelineFromChain($doc, $history)
    {
        // Event: Document created/submitted
        $history->push((object)[
            'status' => 'pending',
            'label' => 'Tài liệu được tạo',
            'submitted_at' => $doc->created_at,
            'submittedByUser' => $doc->author,
            'reviewedByUser' => null,
            'reviewed_at' => null,
            'rejected_reason' => null,
        ]);
        
        // Event: Reviewed (approved/rejected)
        if ($doc->reviewed_at) {
            $history->push((object)[
                'status' => $doc->status,
                'label' => $doc->status === 'approved' ? 'Được phê duyệt' : 'Bị từ chối',
                'submitted_at' => $doc->reviewed_at,
                'submittedByUser' => $doc->author,
                'reviewedByUser' => $doc->reviewer,
                'reviewed_at' => $doc->reviewed_at,
                'rejected_reason' => $doc->rejected_reason,
            ]);
        }
        
        // Process children (drafts created from this document)
        foreach ($doc->histories as $child) {
            $this->buildTimelineFromChain($child, $history);
        }
    }
    
    public function closeHistoryModal()
    {
        $this->showHistoryModal = false;
        $this->historyDocumentId = null;
        $this->submissionHistory = [];
    }

    public function render()
    {
        // Counts for statistics row
        $pendingCount = Document::where('status', 'pending')->whereNull('parent_document_id')->count();
        $approvedTodayCount = Document::where('status', 'approved')
            ->whereNull('parent_document_id')
            ->where('reviewed_at', '>=', now()->startOfDay())
            ->count();
        $rejectedTodayCount = Document::where('status', 'rejected')
            ->whereNull('parent_document_id')
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

        $documents = $query->with(['author', 'category', 'product'])
            ->with(['parentDocument' => function($q) {
                $q->withTrashed();
            }])
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
        
        // Add badge type detection and metadata for each document
        foreach ($documents as $doc) {
            // Get submission history for this draft document
            $submissions = DocumentRelationship::where('draft_document_id', $doc->id)
                ->orderBy('submitted_at', 'desc')
                ->get();
            
            $currentSubmission = $submissions->first();
            $previousRejection = $submissions->where('status', 'rejected')->first();
            
            // For edit submissions (LUỒNG 2), also check other drafts of same parent for rejections
            if ($doc->parent_document_id && !$previousRejection) {
                $previousRejection = DocumentRelationship::where('parent_document_id', $doc->parent_document_id)
                    ->where('draft_document_id', '!=', $doc->id)
                    ->where('status', 'rejected')
                    ->orderBy('reviewed_at', 'desc')
                    ->first();
            }
            
            // Determine badge type based on submission history
            if ($currentSubmission) {
                if ($currentSubmission->relationship_type === 'new_submission') {
                    // Check if this is a resubmission (has previous rejected submissions OR has rejected_reason field)
                    if ($previousRejection || $doc->rejected_reason) {
                        $doc->badge_type = 'resubmit'; // GỬI LẠI (ĐÃ SỬA LỖI)
                        $doc->previous_rejection = $previousRejection ?? (object)[
                            'rejected_reason' => $doc->rejected_reason,
                            'reviewed_at' => $doc->reviewed_at ?? $doc->updated_at
                        ];
                    } else {
                        $doc->badge_type = 'new'; // ĐĂNG MỚI (truly first time)
                    }
                } elseif ($currentSubmission->relationship_type === 'edit_submission') {
                    $doc->badge_type = 'update'; // XIN CẬP NHẬT (editing published doc)
                } else {
                    $doc->badge_type = 'new'; // default
                }
                
                $doc->current_submission = $currentSubmission;
            } else {
                // FALLBACK: Use old system (parent_document_id, status) when relationship records don't exist yet
                if ($doc->parent_document_id) {
                    // Has parent = update to published document
                    $doc->badge_type = 'update';
                } else {
                    // Check if this is a resubmission (author has previous rejected docs with similar title)
                    $titleBase = trim(explode('(', $doc->title)[0]);
                    $hasBeenRejected = Document::where('author_id', $doc->author_id)
                        ->where('id', '!=', $doc->id)
                        ->where('status', 'rejected')
                        ->where('title', 'like', '%' . $titleBase . '%')
                        ->exists();
                    
                    if ($hasBeenRejected || $doc->rejected_reason) {
                        $doc->badge_type = 'resubmit';
                        
                        // Find the most recent rejection for display
                        $prevRejected = Document::where('author_id', $doc->author_id)
                            ->where('id', '!=', $doc->id)
                            ->where('status', 'rejected')
                            ->latest('updated_at')
                            ->first();
                        
                        if ($prevRejected) {
                            $doc->previous_rejection = (object)[
                                'rejected_reason' => $prevRejected->rejected_reason,
                                'reviewed_at' => $prevRejected->reviewed_at ?? $prevRejected->updated_at
                            ];
                        }
                    } else {
                        $doc->badge_type = 'new';
                    }
                }
            }
            
            $doc->submission_count = $submissions->count();
        }

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
