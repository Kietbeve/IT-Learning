<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Database\factories\OrderFactory;
use App\Models\User;
use Modules\Payment\Models\OrderItem;
use Modules\Payment\Models\Payment;
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_code', 'order_type', 'subscription_package_key', 
        'user_id', 'guest_name', 'guest_email', 'total_amount', 
        'payment_status', 'order_status', 'paid_at', 'canceled_at', 'completed_at',
        'download_token', 'guest_download_limit', 'guest_download_count'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'canceled_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
