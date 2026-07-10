<?php

namespace Modules\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;
use Modules\Document\Models\Document;

class DocumentPurchaseSuccessNotification extends Notification
{
    use Queueable;

    protected $document;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, $order)
    {
        $this->document = $document;
        $this->order = $order;
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
            ->subject('Xác nhận mua tài liệu thành công - IT-Learning')
            ->markdown('payment::emails.document.success', [
                'user' => $notifiable,
                'document' => $this->document,
                'order' => $this->order,
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
            'title' => 'Mua tài liệu thành công',
            'message' => 'Bạn đã mua thành công tài liệu: '.Str::limit($this->document->title, 50),
            'icon' => '📄',
            'url' => route('user.purchases'),
            'type' => 'success',
        ];
    }
}
