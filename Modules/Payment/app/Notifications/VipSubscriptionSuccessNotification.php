<?php

namespace Modules\Payment\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;
use Modules\Payment\Models\Order;

class VipSubscriptionSuccessNotification extends Notification
{
    use Queueable, SerializesModels;

    protected $order;

    protected $package;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->package = config('subscription.packages.'.$order->subscription_package_key);
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
            ->subject('Xác nhận thanh toán hóa đơn dịch vụ - IT-Learning')
            ->markdown('payment::emails.vip.success', [
                'user' => $notifiable,
                'order' => $this->order,
                'package' => $this->package,
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
            'type' => 'vip_success',
            'title' => 'Thanh toán VIP thành công',
            'message' => 'Bạn đã đăng ký thành công gói '.($this->package['name'] ?? 'VIP').'. Cảm ơn bạn!',
            'order_code' => $this->order->order_code,
            'url' => route('user.subscription'),
            'icon' => '👑',
        ];
    }
}
