<?php

namespace Modules\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Payment\Models\PayoutRequest;

class PayoutRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $payout;

    /**
     * Create a new notification instance.
     */
    public function __construct(PayoutRequest $payout)
    {
        $this->payout = $payout;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $amount = number_format($this->payout->amount) . 'đ';
        return (new MailMessage)
            ->subject('Yêu cầu rút tiền bị từ chối')
            ->greeting('Xin chào ' . $notifiable->name . ',')
            ->line('Rất tiếc, yêu cầu rút tiền với số tiền **' . $amount . '** của bạn đã bị từ chối.')
            ->line('Lý do từ chối: **' . $this->payout->rejection_reason . '**')
            ->line('Số dư của bạn đã được hoàn lại. Vui lòng kiểm tra lại thông tin và tạo lại yêu cầu nếu cần.')
            ->action('Xem chi tiết giao dịch', route('contributor.payout-request'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $amount = number_format($this->payout->amount) . 'đ';
        return [
            'type' => 'payout_rejected',
            'title' => 'Rút tiền thất bại',
            'message' => 'Yêu cầu rút tiền ' . $amount . ' bị từ chối. Lý do: ' . $this->payout->rejection_reason,
            'amount' => $this->payout->amount,
            'url' => route('contributor.payout-request'),
        ];
    }
}
