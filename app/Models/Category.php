<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Category extends Model


{

    use HasFactory;
    protected $fillable = ['id', 'name', 'description', 'image', 'is_active'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->id)) {
                $latestCategory = Category::orderBy('id', 'desc')->first();
                if ($latestCategory) {
                    $numericPart = (int) substr($latestCategory->id, 2);
                    $newId = 'ca' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'ca001';
                }
                $category->id = $newId;
            }
        });
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
