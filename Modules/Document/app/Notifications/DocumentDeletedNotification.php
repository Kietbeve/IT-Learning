<?php

namespace Modules\Document\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DocumentDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $documentTitle;

    public function __construct(string $documentTitle)
    {
        $this->documentTitle = $documentTitle;
    }

    public function via($notifiable)
    {
        return ["database", "mail"];
    }

    public function toMail($notifiable)
    {
        $url = route("contributor.dashboard");

        return (new MailMessage)
            ->subject('Tài liệu của bạn đã bị gỡ - IT-Learning')
            ->markdown('document::emails.deleted', [
                'user' => $notifiable,
                'documentTitle' => $this->documentTitle,
                'url' => $url,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            "title" => "Tài liệu bị xóa",
            "message" => "Tài liệu " . $this->documentTitle . " của bạn đã bị quản trị viên gỡ bỏ khỏi hệ thống.",
            "action_url" => route("contributor.dashboard"),
            "icon" => "🗑️",
            "icon_color" => "text-red-500",
        ];
    }
}