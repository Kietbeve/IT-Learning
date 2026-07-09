<?php

namespace Modules\Document\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Document\Models\Document;

class DocumentApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function via($notifiable)
    {
        return ["database", "mail"];
    }

    public function toMail($notifiable)
    {
        $url = route("documents.show", ["id" => $this->document->id, "slug" => $this->document->slug]);

        return (new MailMessage)
            ->subject('Tài liệu của bạn đã được duyệt - IT-Learning')
            ->markdown('document::emails.approved', [
                'user' => $notifiable,
                'document' => $this->document,
                'url' => $url,
            ]);
    }

    public function toArray($notifiable)
    {
        return [
            "title" => "Tài liệu được duyệt",
            "message" => "Tài liệu " . $this->document->title . " của bạn đã được quản trị viên phê duyệt thành công.",
            "action_url" => route("documents.show", ["id" => $this->document->id, "slug" => $this->document->slug]),
            "icon" => "✅",
            "icon_color" => "text-green-500",
        ];
    }
}