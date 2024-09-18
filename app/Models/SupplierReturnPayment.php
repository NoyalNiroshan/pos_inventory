<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierReturnPayment extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'supplier_return_id', 'amount_refunded', 'payment_method', 'payment_date'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplierReturnPayment) {
            if (empty($supplierReturnPayment->id)) {
                $latestReturnPayment = SupplierReturnPayment::orderBy('id', 'desc')->first();
                if ($latestReturnPayment) {
                    $numericPart = (int) substr($latestReturnPayment->id, 3);
                    $newId = 'srp' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'srp001';
                }
                $supplierReturnPayment->id = $newId;
            }
        });
    }

    public function supplierReturn()
    {
        return $this->belongsTo(SupplierReturn::class, 'supplier_return_id');
    }
}
