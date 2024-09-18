<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subcategory extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'category_id', 'name', 'description', 'image', 'is_active'];

    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($subcategory) {
            if (empty($subcategory->id)) {
                $latestSubcategory = Subcategory::orderBy('id', 'desc')->first();
                if ($latestSubcategory) {
                    $numericPart = (int) substr($latestSubcategory->id, 2);
                    $newId = 'sc' . str_pad($numericPart + 1, 3, '0', STR_PAD_LEFT);
                } else {
                    $newId = 'sc001';
                }
                $subcategory->id = $newId;
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }
}
