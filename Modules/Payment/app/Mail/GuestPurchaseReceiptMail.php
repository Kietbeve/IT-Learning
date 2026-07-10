<?php

namespace Modules\Payment\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\Payment\Models\Order;

use Illuminate\Contracts\Queue\ShouldQueue;

class GuestPurchaseReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Biên lai mua tài liệu - IT-Learning')
                    ->view('payment::emails.guest-purchase-receipt');
    }
}
