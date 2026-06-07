<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Database\factories\OrderItemFactory;
use Modules\Payment\Models\Order;
use Modules\Payment\Models\Product;
use Modules\Document\Models\Document;
class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'order_id', 'product_id', 'document_id', 'document_title_snapshot',
        'unit_price', 'quantity', 'subtotal', 'contributor_amount', 'platform_amount'
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function document() { return $this->belongsTo(Document::class); }
}
