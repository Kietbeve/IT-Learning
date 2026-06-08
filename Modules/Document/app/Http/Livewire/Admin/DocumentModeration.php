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

        $doc->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'published_at' => now(),
        ]);

        $this->dispatch('notify', ['type' => 'success', 'message' => 'Phê duyệt tài liệu thành công.']);
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
            $doc->update([
                'status' => 'rejected',
                'rejected_reason' => $this->rejectionReason,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            $this->dispatch('close-modal', 'reject-modal');
            $this->dispatch('notify', ['type' => 'success', 'message' => 'Từ chối phê duyệt tài liệu thành công.']);
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

        // Main moderation query
        $query = Document::where('status', 'pending');

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
