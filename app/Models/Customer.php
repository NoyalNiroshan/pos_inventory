<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'email', 'phone', 'address', 'loyalty_points'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->id)) {
                $latestCustomer = Customer::orderBy('id', 'desc')->first();
                if ($latestCustomer) {
                    $numericPart = (int) substr($latestCustomer->id, 3);
                    $newId = 'cus' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'cus001';
                }
                $customer->id = $newId;
            }
        });
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }

    public function returns()
    {
        return $this->hasMany(CustomerReturn::class, 'customer_id');
    }
}
