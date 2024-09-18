<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CouponCode extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'code', 'discount_type', 'discount_value', 'start_date', 'end_date', 'usage_limit', 'usage_count', 'is_active'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($couponCode) {
            if (empty($couponCode->id)) {
                $latestCouponCode = CouponCode::orderBy('id', 'desc')->first();
                if ($latestCouponCode) {
                    $numericPart = (int) substr($latestCouponCode->id, 2);
                    $newId = 'cp' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'cp001';
                }
                $couponCode->id = $newId;
            }
        });
    }
}
