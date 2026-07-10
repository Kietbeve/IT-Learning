<?php

namespace Modules\Payment\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'type', 'amount', 'balance_before', 'balance_after',
        'reference_type', 'reference_id', 'note', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
