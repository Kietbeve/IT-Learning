<?php

namespace Modules\Document\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Document\Models\DocumentReport;

class DocumentReportApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $report;

    public function __construct(DocumentReport $report)
    {
        $this->report = $report;
    }

    public function via($notifiable)
    {
        return ["database", "mail"];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Tài liệu của bạn bị gỡ do vi phạm - IT-Learning')
            ->greeting('Xin chào ' . $notifiable->name . ',')
            ->line('Tài liệu **' . ($this->report->document->title ?? 'N/A') . '** của bạn đã bị gỡ khỏi hệ thống do vi phạm chính sách.')
            ->line('**Lý do vi phạm:** ' . $this->report->reason)
            ->line('Nếu bạn cho rằng đây là một sự nhầm lẫn, vui lòng liên hệ với ban quản trị để được hỗ trợ.')
            ->action('Trang chủ', url('/'))
            ->line('Cảm ơn bạn đã sử dụng nền tảng của chúng tôi.');
    }

    public function toArray($notifiable)
    {
        return [
            "title" => "Tài liệu bị gỡ do vi phạm",
            "message" => "Tài liệu '" . ($this->report->document->title ?? 'N/A') . "' của bạn đã bị gỡ. Lý do: " . $this->report->review_note,
            "action_url" => "#",
            "icon" => "❌",
            "icon_color" => "text-red-500",
        ];
    }
}
