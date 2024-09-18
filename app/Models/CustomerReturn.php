<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerReturn extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'sale_id', 'customer_id', 'return_date', 'total_amount', 'reason'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customerReturn) {
            if (empty($customerReturn->id)) {
                $latestReturn = CustomerReturn::orderBy('id', 'desc')->first();
                if ($latestReturn) {
                    $numericPart = (int) substr($latestReturn->id, 3);
                    $newId = 'crt' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'crt001';
                }
                $customerReturn->id = $newId;
            }
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function returnItems()
    {
        return $this->hasMany(CustomerReturnItem::class, 'customer_return_id');
    }
}
