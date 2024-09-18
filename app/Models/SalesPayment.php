<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesPayment extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'sale_id', 'amount_paid', 'payment_method', 'payment_date', 'balance_due'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($salesPayment) {
            if (empty($salesPayment->id)) {
                $latestPayment = SalesPayment::orderBy('id', 'desc')->first();
                if ($latestPayment) {
                    $numericPart = (int) substr($latestPayment->id, 2);
                    $newId = 'sp' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'sp001';
                }
                $salesPayment->id = $newId;
            }
        });
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
