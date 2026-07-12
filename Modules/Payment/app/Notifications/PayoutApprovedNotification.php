<?php

namespace Modules\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Payment\Models\PayoutRequest;

class PayoutApprovedNotification extends Notification implements ShouldQueue
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
            ->subject('Yêu cầu rút tiền đã được duyệt')
            ->greeting('Xin chào ' . $notifiable->name . '!')
            ->line('Tin vui đây! Yêu cầu rút tiền với số tiền **' . $amount . '** của bạn đã được admin phê duyệt và chuyển khoản.')
            ->line('Bạn vui lòng kiểm tra tài khoản ngân hàng để xác nhận nhé.')
            ->action('Xem chi tiết giao dịch', route('contributor.payout-request'))
            ->line('Cảm ơn bạn đã đóng góp nội dung chất lượng cho nền tảng của chúng tôi!');
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
            'type' => 'payout_approved',
            'title' => 'Rút tiền thành công',
            'message' => 'Yêu cầu rút tiền ' . $amount . ' của bạn đã được duyệt và chuyển khoản.',
            'amount' => $this->payout->amount,
            'url' => route('contributor.payout-request'),
        ];
    }
}
