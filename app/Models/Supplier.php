<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    // Define the table name if it's not pluralized or different
    protected $table = 'suppliers'; // Define the table name

    protected $primaryKey = 'id'; // Set the primary key
    public $incrementing = false; // Disable auto-incrementing

    protected $fillable = [
        'id', // Custom ID
        'company_name',
        'registerNum',
        'email',
        'address',
        'phone_numbers',
        'has_discount',
        'discount_type',
        'discount_value',
        'rating',
        'tax_identification_number',
        'supplier_type',
        'delivery_methods',
        'account_name',  // New field for banking details
        'account_number', // New field for banking details
        'bank_name', // New field for banking details
        'ifsc_code', // New field for banking details
    ];

    // Cast JSON fields
    protected $casts = [
        'phone_numbers' => 'array',
        'banking_details' => 'array',
        'has_discount' => 'boolean',
        'rating' => 'decimal:2',
    ];

    // Override the boot method to generate custom ID
    protected static function boot()
    {
        parent::boot();

        // Automatically set custom ID pattern when creating a new supplier
        static::creating(function ($model) {
            $model->id = self::generateCustomId();
        });
    }

    /**
     * Generate custom ID for the supplier in the format 'sup001', 'sup002', etc.
     */
    public static function generateCustomId()
    {
        $lastSupplier = self::orderBy('id', 'desc')->first();

        if (!$lastSupplier) {
            return 'sup001'; // Default if no previous supplier exists
        }

        // Extract numeric part from last ID and increment it
        $lastId = intval(substr($lastSupplier->id, 3));
        $newId = $lastId + 1;

        // Return the new ID with leading zeroes
        return 'sup' . str_pad($newId, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Define any relationships (e.g., products, payments) if necessary
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function payments()
    {
        return $this->hasMany(SupplierStockPayment::class);
    }
}
