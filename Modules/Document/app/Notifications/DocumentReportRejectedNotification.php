<?php

namespace Modules\Document\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Document\Models\DocumentReport;

class DocumentReportRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;

    public function __construct(DocumentReport $report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ["database"];
    }

    public function toArray($notifiable)
    {
        return [
            "title" => "Báo cáo vi phạm bị từ chối",
            "message" => "Báo cáo của bạn cho tài liệu '" . ($this->report->document->title ?? 'N/A') . "' đã bị từ chối" . ($this->report->review_note ? ". Lý do: " . $this->report->review_note : "."),
            "action_url" => route("documents.show", ["id" => $this->report->document_id]),
            "icon" => "⚠️",
            "icon_color" => "text-yellow-500",
        ];
    }
}
