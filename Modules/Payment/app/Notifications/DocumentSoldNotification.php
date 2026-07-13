<?php

namespace Modules\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Modules\Document\Models\Document;
use Modules\Payment\Models\Order;

class DocumentSoldNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Document $document;
    public Order $order;
    public int $amount;
    public string $type;

    /**
     * Create a new notification instance.
     */
    public function __construct(Document $document, Order $order, int $amount, string $type = 'cash')
    {
        $this->document = $document;
        $this->order = $order;
        $this->amount = $amount;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Tài liệu của bạn đã được mua!',
            'message' => 'Người dùng vừa tải tài liệu "' . $this->document->title . '" của bạn bằng ' . ($this->type === 'vip' ? 'lượt tải VIP' : 'tiền mặt') . '.',
            'document_id' => $this->document->id,
            'order_code' => $this->order->order_code,
            'amount' => $this->amount,
            'type' => 'document_sold',
            'link' => route('contributor.earnings'),
        ];
    }
}
