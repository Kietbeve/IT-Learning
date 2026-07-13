<?php

namespace Modules\Document\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Modules\Document\Models\DocumentReport;

class DocumentReportResolvedForReporterNotification extends Notification implements ShouldQueue
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
            "title" => "Báo cáo đã được xử lý",
            "message" => "Cảm ơn bạn! Báo cáo của bạn cho tài liệu '" . ($this->report->document->title ?? 'N/A') . "' đã được duyệt và tài liệu đã bị gỡ.",
            "action_url" => "#",
            "icon" => "✅",
            "icon_color" => "text-green-500",
        ];
    }
}
