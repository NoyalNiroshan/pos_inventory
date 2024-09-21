<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StockIn extends Model
{
    use HasFactory;

    protected $table = 'stock_in';
    protected $fillable = ['id', 'supplier_id', 'stock_in_date', 'batch_no', 'total_amount'];

    protected $casts = [
        'stock_in_date' => 'date',
        'expiration_date' => 'date',
        'manufacturing_date' => 'date',
    ];


    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($stockIn) {
            $latestStockIn = StockIn::orderBy('id', 'desc')->first();
            $numericPart = $latestStockIn ? (int) substr($latestStockIn->id, 2) : 0;
            $stockIn->id = 'st' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
        });
    }

    public function items()
    {
        return $this->hasMany(StockInItem::class, 'stock_in_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function payments()
    {
        return $this->hasMany(SupplierStockPayment::class, 'stock_in_id');
    }

    public function calculateTotalAmount()
    {
        return $this->items->sum(function ($item) {
            $discountedPrice = $item->discount_type === 'percentage'
                ? $item->price_per_unit * (1 - $item->discount / 100)
                : $item->price_per_unit - $item->discount;
            return $item->quantity * $discountedPrice;
        });
    }
}
