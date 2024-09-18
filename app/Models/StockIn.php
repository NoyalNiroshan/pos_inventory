<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockIn extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'supplier_id', 'stock_in_date', 'batch_no', 'total_amount'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stockIn) {
            if (empty($stockIn->id)) {
                $latestStockIn = StockIn::orderBy('id', 'desc')->first();
                if ($latestStockIn) {
                    $numericPart = (int) substr($latestStockIn->id, 2);
                    $newId = 'st' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'st001';
                }
                $stockIn->id = $newId;
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(StockInItem::class, 'stock_in_id');
    }

    public function payments()
    {
        return $this->hasMany(SupplierStockPayment::class, 'stock_in_id');
    }
}
