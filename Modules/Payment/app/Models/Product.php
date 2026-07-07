<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Document\Models\Document;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = ['document_id', 'name', 'price', 'sale_price', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function statusHistories()
    {
        return $this->hasMany(ProductStatusHistory::class);
    }

    protected static function booted()
    {
        static::created(function ($product) {
            $product->recordStatusHistory('Tạo mới sản phẩm');
        });

        static::updated(function ($product) {
            if ($product->wasChanged(['price', 'sale_price', 'is_active'])) {
                $changes = [];
                if ($product->wasChanged('price')) {
                    $changes[] = 'giá';
                }
                if ($product->wasChanged('sale_price')) {
                    $changes[] = 'giá khuyến mãi';
                }
                if ($product->wasChanged('is_active')) {
                    $changes[] = 'trạng thái';
                }
                
                $note = 'Cập nhật: ' . implode(', ', $changes);
                $product->recordStatusHistory($note);
            }
        });
    }

    public function recordStatusHistory($note = null)
    {
        ProductStatusHistory::create([
            'product_id' => $this->id,
            'status' => $this->is_active ? 'active' : 'inactive',
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'changed_by' => auth()->id(),
            'note' => $note,
            'created_at' => now(),
        ]);
    }
}
