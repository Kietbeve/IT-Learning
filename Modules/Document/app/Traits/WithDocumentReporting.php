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

        \Modules\Document\Models\DocumentReport::create([
            'user_id' => Auth::id(),
            'document_id' => $this->documentId,
            'reason' => $this->reportReason,
            'details' => $this->reportDetails,
            'status' => 'pending'
        ]);

        $this->showReportModal = false;
        $this->dispatch('notify', ['type' => 'success', 'message' => 'Báo cáo vi phạm đã được gửi thành công. Admin sẽ kiểm duyệt tệp này.']);
    }
}
