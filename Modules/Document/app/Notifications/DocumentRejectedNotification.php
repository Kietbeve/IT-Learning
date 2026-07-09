<?php

namespace Modules\Document\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Document\Models\Document;

class DocumentRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;
    protected $reason;

    public function __construct(Document $document, string $reason)
    {
        $this->document = $document;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ["database", "mail"];
    }

    public function toMail($notifiable)
    {
        $url = route("contributor.dashboard");

        return (new MailMessage)
            ->subject('Tài liệu của bạn cần chỉnh sửa - IT-Learning')
            ->markdown('document::emails.rejected', [
                'user' => $notifiable,
                'document' => $this->document,
                'reason' => $this->reason,
                'url' => $url,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            "title" => "Tài liệu bị từ chối",
            "message" => "Tài liệu " . $this->document->title . " của bạn đã bị từ chối duyệt. Lý do: " . $this->reason,
            "action_url" => route("contributor.dashboard"),
            "icon" => "❌",
            "icon_color" => "text-red-500",
        ];
    }
}