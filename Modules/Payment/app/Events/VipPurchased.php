<?php

namespace Modules\Payment\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Modules\Payment\Models\Order;

class VipPurchased
{
    use Dispatchable, SerializesModels;

    public User $user;

    public Order $order;

    public string $packageKey;

    public function __construct(User $user, Order $order, string $packageKey)
    {
        $this->user = $user;
        $this->order = $order;
        $this->packageKey = $packageKey;
    }
}
