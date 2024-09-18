<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerReturnPayment extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'customer_return_id', 'amount_refunded', 'payment_method', 'payment_date'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customerReturnPayment) {
            if (empty($customerReturnPayment->id)) {
                $latestReturnPayment = CustomerReturnPayment::orderBy('id', 'desc')->first();
                if ($latestReturnPayment) {
                    $numericPart = (int) substr($latestReturnPayment->id, 3);
                    $newId = 'crp' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'crp001';
                }
                $customerReturnPayment->id = $newId;
            }
        });
    }

    public function customerReturn()
    {
        return $this->belongsTo(CustomerReturn::class, 'customer_return_id');
    }
}
