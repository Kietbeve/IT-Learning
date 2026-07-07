<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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
        'download_token', 'guest_download_limit', 'guest_download_count',
        'expires_at', 'checkout_data',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'canceled_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'checkout_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public static function expirePendingOrders($userId)
    {
        return static::where('user_id', $userId)
            ->where('payment_status', 'pending')
            ->where('expires_at', '<=', now())
            ->update([
                'payment_status' => 'expired',
                'order_status' => 'expired',
                'canceled_at' => now(),
            ]);
    }
}
