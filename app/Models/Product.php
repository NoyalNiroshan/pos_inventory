<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'brand_id', 'subcategory_id', 'name', 'stock_type', 'serial_number', 'is_active', 'in_stock', 'on_sale', 'image', 'description'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->id)) {
                $brandId = $product->brand_id;
                $subcategoryId = $product->subcategory_id;
                $latestProduct = Product::where('brand_id', $brandId)
                    ->where('subcategory_id', $subcategoryId)
                    ->orderBy('id', 'desc')
                    ->first();

                if ($latestProduct) {
                    $numericPart = (int) substr($latestProduct->id, -3);
                    $newId = $brandId . '-' . $subcategoryId . '-pr' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = $brandId . '-' . $subcategoryId . '-pr001';
                }

                $product->id = $newId;
            }
        });
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'subcategory_id');
    }

    public function stockInItems()
    {
        return $this->hasMany(StockInItem::class, 'product_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class, 'product_id');
    }
}
