<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierReturnItem extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'supplier_return_id', 'product_id', 'batch_no', 'quantity_returned', 'unit', 'price_per_unit', 'total_return_price'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplierReturnItem) {
            if (empty($supplierReturnItem->id)) {
                $latestReturnItem = SupplierReturnItem::orderBy('id', 'desc')->first();
                if ($latestReturnItem) {
                    $numericPart = (int) substr($latestReturnItem->id, 3);
                    $newId = 'sri' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'sri001';
                }
                $supplierReturnItem->id = $newId;
            }
        });
    }

    public function supplierReturn()
    {
        return $this->belongsTo(SupplierReturn::class, 'supplier_return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
