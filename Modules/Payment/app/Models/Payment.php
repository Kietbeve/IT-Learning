<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Database\factories\PaymentFactory;
use Modules\Payment\Models\Order;
class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id', 'provider', 'transaction_code', 'provider_order_code', 
        'amount', 'status', 'raw_response', 'paid_at'
    ];

    protected $casts = [
        'raw_response' => 'array',
        'paid_at' => 'datetime',
    ];

    public function order() { return $this->belongsTo(Order::class); }
}
