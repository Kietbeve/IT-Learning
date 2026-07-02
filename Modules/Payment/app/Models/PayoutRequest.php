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
    
    public function walletTransactions()
    {
        return $this->hasMany(WalletTransaction::class, 'reference_id')
            ->where('reference_type', 'payout_request');
    }
    
    public function rejectionTransaction()
    {
        return $this->hasOne(WalletTransaction::class, 'reference_id')
            ->where('reference_type', 'payout_request')
            ->where('type', 'payout_rejected');
    }
    
    public function getRejectionReasonAttribute()
    {
        if ($this->status !== 'rejected') {
            return null;
        }
        
        return $this->rejectionTransaction?->note;
    }

    public function getReceiptUrlAttribute()
    {
        if (!$this->receipt_image) return null;
        if (str_starts_with($this->receipt_image, 'http')) return $this->receipt_image;
        if (str_contains($this->receipt_image, 'payout_receipts/')) {
            $publicUrl = config('filesystems.disks.r2.url');
            return rtrim($publicUrl, '/') . '/' . ltrim($this->receipt_image, '/');
        }
        return asset('storage/' . $this->receipt_image);
    }
}
