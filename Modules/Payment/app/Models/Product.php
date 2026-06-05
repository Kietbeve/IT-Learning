<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Payment\Database\factories\ProductFactory;
use Modules\Payment\Models\ProductStatusHistory;
use Modules\Document\Models\Document;
class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   protected $fillable = ['document_id', 'name', 'price', 'sale_price', 'is_active'];
    
    protected $casts = ['is_active' => 'boolean'];

    public function document() { return $this->belongsTo(Document::class); }
    public function statusHistories() { return $this->hasMany(ProductStatusHistory::class); }
}
