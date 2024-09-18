<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockInItem extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'stock_in_id', 'product_id', 'quantity', 'unit', 'price_per_unit', 'expiration_date', 'discount', 'manufacturing_date'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stockInItem) {
            if (empty($stockInItem->id)) {
                $latestStockInItem = StockInItem::orderBy('id', 'desc')->first();
                if ($latestStockInItem) {
                    $numericPart = (int) substr($latestStockInItem->id, 3);
                    $newId = 'sti' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'sti001';
                }
                $stockInItem->id = $newId;
            }
        });
    }

    public function stockIn()
    {
        return $this->belongsTo(StockIn::class, 'stock_in_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
