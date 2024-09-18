<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerReturnItem extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'customer_return_id', 'product_id', 'quantity_returned', 'unit', 'price_per_unit', 'total_return_price'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customerReturnItem) {
            if (empty($customerReturnItem->id)) {
                $latestReturnItem = CustomerReturnItem::orderBy('id', 'desc')->first();
                if ($latestReturnItem) {
                    $numericPart = (int) substr($latestReturnItem->id, 3);
                    $newId = 'cri' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'cri001';
                }
                $customerReturnItem->id = $newId;
            }
        });
    }

    public function customerReturn()
    {
        return $this->belongsTo(CustomerReturn::class, 'customer_return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
