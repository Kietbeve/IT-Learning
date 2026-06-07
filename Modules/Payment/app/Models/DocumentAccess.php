<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Database\factories\DocumentAccessFactory;
use App\Models\User;
use Modules\Payment\Models\OrderItem;
use Modules\Document\Models\Document;
class DocumentAccess extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   protected $fillable = ['user_id', 'document_id', 'order_item_id', 'access_type', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }
    public function document() { return $this->belongsTo(Document::class); }
    public function orderItem() { return $this->belongsTo(OrderItem::class); }
}
