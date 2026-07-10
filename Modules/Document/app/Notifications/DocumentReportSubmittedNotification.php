<?php

namespace Modules\Document\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Document\Models\DocumentReport;

class DocumentReportSubmittedNotification extends Notification implements ShouldQueue
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
            "title" => "Báo cáo vi phạm thành công",
            "message" => "Báo cáo vi phạm cho tài liệu '" . ($this->report->document->title ?? 'N/A') . "' đã được gửi thành công. Admin đang xử lý.",
            "action_url" => route("documents.show", ["id" => $this->report->document_id]),
            "icon" => "🛡️",
            "icon_color" => "text-blue-500",
        ];
    }
}
