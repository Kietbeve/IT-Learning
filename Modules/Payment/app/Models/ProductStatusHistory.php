<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Database\factories\ProductStatusHistoryFactory;
use App\Models\User;
use Modules\Payment\Models\Product;
class ProductStatusHistory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    public $timestamps = false; // Chỉ xài created_at
    protected $fillable = ['product_id', 'status', 'price', 'sale_price', 'changed_by', 'note', 'created_at'];

    public function product() { return $this->belongsTo(Product::class); }
    public function changedBy() { return $this->belongsTo(User::class, 'changed_by'); }
}
