<?php

namespace Modules\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;
use Modules\Document\Models\Document;

class DocumentVipPurchaseNotification extends Notification implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $document;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->onQueue('high');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Xác nhận mua tài liệu bằng VIP - IT-Learning')
            ->markdown('payment::emails.document.vip_success', [
                'user' => $notifiable,
                'document' => $this->document,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'document_vip_purchase',
            'title' => 'Đã mua tài liệu bằng VIP',
            'message' => 'Bạn đã dùng 1 lượt VIP để mua tài liệu "'.\Illuminate\Support\Str::limit($this->document->title, 40).'". Lượt tải còn lại: '.$notifiable->vip_download_quota,
            'url' => route('documents.show', [$this->document->id, \Illuminate\Support\Str::slug($this->document->title)]),
            'icon' => '💎',
        ];
    }
}
