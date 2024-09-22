<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupplierStockPayment extends Model
{
    use HasFactory;

    protected $table = 'supplier_stock_payments';

    protected $fillable = [
        'id', 'stock_in_id', 'amount_paid', 'balance_due', 'payment_method', 'payment_date'
    ];

    protected $casts = [
        'payment_date' => 'date',
    ];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $latestPayment = SupplierStockPayment::orderBy('id', 'desc')->first();
            $numericPart = $latestPayment ? (int) substr($latestPayment->id, 3) : 0;
            $payment->id = 'ssp' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
        });
    }

    public function stockIn()
    {
        return $this->belongsTo(StockIn::class, 'stock_in_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
