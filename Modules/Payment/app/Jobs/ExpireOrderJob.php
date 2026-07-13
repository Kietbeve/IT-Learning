<?php

namespace Modules\Payment\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\Payment\Models\Order;

class ExpireOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $orderId
    ) {}

    public function handle(): void
    {
        $order = Order::find($this->orderId);

        if (! $order) {
            Log::warning('ExpireOrderJob: Order not found', ['order_id' => $this->orderId]);

            return;
        }

        if ($order->payment_status === 'pending' && $order->order_status === 'pending') {
            $order->update([
                'payment_status' => 'expired',
                'order_status' => 'expired',
                'canceled_at' => now(),
            ]);

            Log::info('ExpireOrderJob: Order expired', [
                'order_id' => $order->id,
                'order_code' => $order->order_code,
            ]);
        } else {
            Log::info('ExpireOrderJob: Order already processed', [
                'order_id' => $order->id,
                'payment_status' => $order->payment_status,
                'order_status' => $order->order_status,
            ]);
        }
    }
}
