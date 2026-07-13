<?php

namespace Modules\Document\Http\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Livewire\WithPagination;
use Modules\Document\Models\DocumentReport as Report;

class DocumentReport extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = 'pending';

    public $reasonFilter = 'all';

    public $selectedReportId = null;

    public $unpublishReason = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'pending'],
        'reasonFilter' => ['except' => 'all'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingReasonFilter()
    {
        $this->resetPage();
    }

    public function openResolveModal($id)
    {
        $this->selectedReportId = $id;
        $this->unpublishReason = '';
        $this->resetValidation();
        $this->dispatch('open-modal', 'report-resolve-modal');
    }

    public function confirmResolve()
    {
        $this->validate([
            'unpublishReason' => 'required|string|max:500',
        ], [
            'unpublishReason.required' => 'Vui lòng nhập lý do gỡ tài liệu.',
            'unpublishReason.max' => 'Lý do không được vượt quá 500 ký tự.',
        ]);

        $report = Report::findOrFail($this->selectedReportId);

        $report->update([
            'status' => 'resolved',
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
            'review_note' => $this->unpublishReason, // We can store it here too for the report record
        ]);

        if ($report->document) {
            $report->document->update(['status' => 'unpublished']);
            if ($report->document->currentVersion) {
                $report->document->currentVersion->update([
                    'status' => 'unpublished',
                ]);
            }
            
            // Notify the author
            if ($report->document->author) {
                $report->document->author->notify(new \Modules\Document\Notifications\DocumentReportApprovedNotification($report));
                $this->dispatch('new-notification');
            }
        }

        // Notify the reporter
        if ($report->user) {
            $report->user->notify(new \Modules\Document\Notifications\DocumentReportResolvedForReporterNotification($report));
            $this->dispatch('new-notification');
        }

        $this->dispatch('close-modal', 'report-resolve-modal');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã phê duyệt báo cáo và gỡ bỏ tài liệu vi phạm thành công.']);
        
        $this->selectedReportId = null;
        $this->unpublishReason = '';
    }

    public function openDismissModal($id)
    {
        $this->selectedReportId = $id;
        $this->dispatch('open-modal', 'report-dismiss-modal');
    }

    public function confirmDismiss()
    {
        $report = Report::findOrFail($this->selectedReportId);

        $report->update([
            'status' => 'dismissed',
            'resolved_by' => Auth::id(),
            'resolved_at' => now(),
            'review_note' => null,
        ]);

        if ($report->user) {
            $report->user->notify(new \Modules\Document\Notifications\DocumentReportRejectedNotification($report));
            $this->dispatch('new-notification');
        }

        $this->dispatch('close-modal', 'report-dismiss-modal');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Đã bác bỏ báo cáo vi phạm thành công.']);
        $this->selectedReportId = null;
    }

    public function getReasonLabel($reason)
    {
        $reasons = [
            'Bản quyền' => 'Vi phạm bản quyền',
            'Nội dung sai' => 'Nội dung sai lệch',
            'File hỏng' => 'Tệp tin lỗi',
            'Spam' => 'Spam / Quảng cáo',
            'Khác' => 'Lý do khác',
        ];

        return $reasons[$reason] ?? $reason;
    }

    public function render()
    {
        // Real database queries instead of mock data
        $query = Report::with(['user', 'document.author', 'resolver'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->reasonFilter !== 'all') {
            $query->where('reason', $this->reasonFilter);
        }

        if (! empty($this->search)) {
            $query->where(function ($q) {
                $q->whereHas('document.currentVersion', function ($q2) {
                    $q2->where('title', 'like', '%'.$this->search.'%');
                })
                    ->orWhereHas('user', function ($q2) {
                        $q2->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhere('details', 'like', '%'.$this->search.'%');
            });
        }

        $reports = $query->paginate(8);

        // Count stats from real database
        $pendingCount = Report::where('status', 'pending')->count();
        $resolvedCount = Report::where('status', 'resolved')->count();
        $dismissedCount = Report::where('status', 'dismissed')->count();
        $totalCount = Report::count();

        return view('document::livewire.admin.document-report', [
            'reports' => $reports,
            'pendingCount' => $pendingCount,
            'resolvedCount' => $resolvedCount,
            'dismissedCount' => $dismissedCount,
            'totalCount' => $totalCount,
            'dbError' => false,
        ])->layout('layouts.admin', [
            'pageTitle' => 'Quản lý báo cáo vi phạm tài liệu',
            'breadcrumb' => new HtmlString('<span class="mx-2">/</span> Admin <span class="mx-2">/</span> Báo cáo vi phạm'),
        ]);
    }
}
