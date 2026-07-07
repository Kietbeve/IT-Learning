<?php

namespace Modules\Payment\Events;


use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Modules\Payment\Models\Order;

class DocumentPurchased
{
    use Dispatchable, SerializesModels;

    public $user;

    public $order;

    public $documentTitle;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, Order $order)
    {
        $this->user = $user;
        $this->order = $order;
        $this->documentTitle = $order->items->first()->document_title_snapshot ?? 'Tài liệu';
    }
}
