<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockInItem extends Model
{
    use HasFactory;

    protected $table = 'stock_in_items';

    protected $fillable = [
        'stock_in_id', 'product_id', 'quantity', 'unit', 'price_per_unit',
        'discount', 'discount_type', 'total_price', 'expiration_date', 'manufacturing_date'
    ];

    protected $casts = [
        'stock_in_date' => 'date',
        'expiration_date' => 'date',
        'manufacturing_date' => 'date',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stockInItem) {
            $latestStockInItem = StockInItem::orderBy('id', 'desc')->first();
            $numericPart = $latestStockInItem ? (int) substr($latestStockInItem->id, 3) : 0;
            $stockInItem->id = 'sti' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
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
