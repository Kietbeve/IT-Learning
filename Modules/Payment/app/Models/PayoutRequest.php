<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Database\factories\PayoutRequestFactory;
use App\Models\User;
class PayoutRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id', 'amount', 'bank_name', 'bank_account_number', 'bank_account_name', 
        'status', 'receipt_image', 'note', 'processed_by', 'processed_at'
    ];
    
    protected $casts = ['processed_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function processor() { return $this->belongsTo(User::class, 'processed_by'); }
}
