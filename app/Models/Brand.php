<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    public $incrementing = false; // Non-incrementing primary key
    protected $keyType = 'string'; // Key is a string
    protected $primaryKey = 'id'; // Explicitly set primary key

    protected $fillable = ['name', 'description', 'image', 'is_active'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->id)) {
                // Get the last brand entry ordered by 'id'
                $latestBrand = Brand::orderBy('id', 'desc')->first();

                if ($latestBrand) {
                    // Extract the numeric part of the 'id' (after the 'br' prefix)
                    $numericPart = (int) substr($latestBrand->id, 2);
                    // Increment the numeric part by 1 and format it with leading zeros
                    $newId = 'br' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    // Start from br001 if no previous records exist
                    $newId = 'br001';
                }

                // Set the new id
                $brand->id = $newId;
            }
        });
    }
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
