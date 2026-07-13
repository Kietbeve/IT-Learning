<?php

namespace Modules\Document\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Models\OrderItem;

class DocumentDownload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false; // Bảng này chỉ dùng downloaded_at

    protected $fillable = ['document_id', 'user_id', 'order_item_id', 'ip_address', 'user_agent', 'source', 'downloaded_at'];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cross-Module
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
