<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierReturn extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'stock_in_id', 'supplier_id', 'batch_no', 'return_date', 'total_amount'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplierReturn) {
            if (empty($supplierReturn->id)) {
                $latestReturn = SupplierReturn::orderBy('id', 'desc')->first();
                if ($latestReturn) {
                    $numericPart = (int) substr($latestReturn->id, 3);
                    $newId = 'srt' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'srt001';
                }
                $supplierReturn->id = $newId;
            }
        });
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function stockIn()
    {
        return $this->belongsTo(StockIn::class, 'stock_in_id');
    }

    public function returnItems()
    {
        return $this->hasMany(SupplierReturnItem::class, 'supplier_return_id');
    }
}
