<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'customer_id', 'sale_date', 'grand_total', 'coupon_code', 'discount_type', 'discount_value', 'final_total'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($sale) {
            if (empty($sale->id)) {
                $latestSale = Sale::orderBy('id', 'desc')->first();
                if ($latestSale) {
                    $numericPart = (int) substr($latestSale->id, 2);
                    $newId = 'sl' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'sl001';
                }
                $sale->id = $newId;
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }

    public function payments()
    {
        return $this->hasMany(SalesPayment::class, 'sale_id');
    }
}
