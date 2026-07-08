<?php

namespace Modules\Payment\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Log;
use Modules\Payment\Models\DocumentAccess;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\OrderItem;

class ClaimGuestPurchases
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return void
     */
    public function handle($event)
    {
        $user = $event->user;
        if (!$user) {
            return;
        }

        $email = $user->email;

        // Tìm các đơn hàng của khách trùng email và đã thanh toán
        $guestOrders = Order::whereNull('user_id')
            ->where('guest_email', $email)
            ->where('payment_status', 'paid')
            ->get();

        if ($guestOrders->isEmpty()) {
            return;
        }

        foreach ($guestOrders as $order) {
            // Cập nhật order gán cho user
            $order->update([
                'user_id' => $user->id,
                'guest_download_limit' => 0, // Bỏ giới hạn tải
            ]);

            // Nếu đơn hàng là document, cấp quyền truy cập
            if ($order->order_type === 'document') {
                foreach ($order->items as $item) {
                    DocumentAccess::updateOrCreate(
                        [
                            'user_id' => $user->id,
                            'document_id' => $item->document_id,
                        ],
                        [
                            'order_item_id' => $item->id,
                            'access_type' => 'purchased',
                        ]
                    );
                }
            }
        }

        Log::info('ClaimGuestPurchases: Đã đồng bộ tài liệu khách cho user.', [
            'user_id' => $user->id,
            'email' => $email,
            'orders_count' => $guestOrders->count(),
        ]);
    }
}
