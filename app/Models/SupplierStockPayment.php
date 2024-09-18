<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierStockPayment extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'stock_in_id', 'supplier_id', 'amount_paid', 'balance_due', 'payment_method', 'payment_date'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplierStockPayment) {
            if (empty($supplierStockPayment->id)) {
                $latestPayment = SupplierStockPayment::orderBy('id', 'desc')->first();
                if ($latestPayment) {
                    $numericPart = (int) substr($latestPayment->id, 3);
                    $newId = 'ssp' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'ssp001';
                }
                $supplierStockPayment->id = $newId;
            }
        });
    }

    public function stockIn()
    {
        return $this->belongsTo(StockIn::class, 'stock_in_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
