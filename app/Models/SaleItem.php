<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SaleItem extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'sale_id', 'product_id', 'batch_no', 'quantity_sold', 'unit', 'price_per_unit', 'discount_type', 'discount_value', 'tax', 'final_total'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($saleItem) {
            if (empty($saleItem->id)) {
                $latestSaleItem = SaleItem::orderBy('id', 'desc')->first();
                if ($latestSaleItem) {
                    $numericPart = (int) substr($latestSaleItem->id, 2);
                    $newId = 'si' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'si001';
                }
                $saleItem->id = $newId;
            }
        });
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
