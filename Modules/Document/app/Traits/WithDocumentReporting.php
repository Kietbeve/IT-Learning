<?php

namespace Modules\Document\Traits;

use Illuminate\Support\Facades\Auth;

trait WithDocumentReporting
{
    public $showReportModal = false;
    public $reportReason = 'Bản quyền';
    public $reportDetails = '';

    public function openReportModal()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $this->reportReason = 'Bản quyền';
        $this->reportDetails = '';
        $this->showReportModal = true;
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
    }

    public function submitReport()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $this->validate([
            'reportReason' => 'required|string',
            'reportDetails' => 'nullable|string|max:1000',
        ]);

        $existingReport = \Modules\Document\Models\DocumentReport::where('document_id', $this->documentId)
            ->where('user_id', Auth::id())
            ->where('status', '!=', 'dismissed')
            ->first();

        if ($existingReport) {
            $this->dispatch('notify', ['type' => 'error', 'message' => 'Bạn đã báo cáo tài liệu này rồi.']);
            $this->showReportModal = false;
            return;
        }

        $report = \Modules\Document\Models\DocumentReport::create([
            'user_id' => Auth::id(),
            'document_id' => $this->documentId,
            'reason' => $this->reportReason,
            'details' => $this->reportDetails,
            'status' => 'pending'
        ]);

        Auth::user()->notify(new \Modules\Document\Notifications\DocumentReportSubmittedNotification($report));
        $this->dispatch('new-notification');

        $this->showReportModal = false;
    }
}
