<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'email', 'address', 'phone_numbers', 'has_discount', 'discount_type', 'discount_value'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplier) {
            if (empty($supplier->id)) {
                $latestSupplier = Supplier::orderBy('id', 'desc')->first();
                if ($latestSupplier) {
                    $numericPart = (int) substr($latestSupplier->id, 3);
                    $newId = 'sup' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'sup001';
                }
                $supplier->id = $newId;
            }
        });
    }

    public function stockIns()
    {
        return $this->hasMany(StockIn::class, 'supplier_id');
    }

    public function payments()
    {
        return $this->hasMany(SupplierStockPayment::class, 'supplier_id');
    }
}
