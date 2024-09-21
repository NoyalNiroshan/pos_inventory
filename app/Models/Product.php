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


    public function getAvailableUnits()
    {
        // Return available units based on stock type
        switch ($this->stock_type) {
            case 'liquid':
                return ['L', 'ml']; // For liquids (e.g., oil, water)
            case 'solid':
                return ['kg', 'g']; // For solids (e.g., raw materials, solid goods)
            case 'dress':
                return ['XL', 'Large', 'Medium', 'Small']; // For clothing or apparel items
            case 'powder':
                return ['kg', 'g']; // For powders (e.g., flour, chemicals)
            case 'gas':
                return ['kg', 'm³']; // For gaseous products
            case 'electronics':
                return ['units']; // For electronic items (e.g., gadgets, devices)
            case 'medicine':
                return ['units']; // For medical supplies or pharmaceuticals
            case 'furniture':
                return ['pieces']; // For furniture items
            case 'cosmetics':
                return ['ml', 'g']; // For cosmetics or beauty products
            case 'food':
                return ['kg', 'g', 'pieces']; // For food products
            case 'beverage':
                return ['L', 'ml']; // For beverage products
            default:
                return [];
        }
    }


}
