<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = ['id', 'name', 'description', 'image', 'is_active'];

    public $incrementing = false;
    protected $keyType = 'string';

    // Automatically generate the custom ID
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->id)) {
                $brand->id = 'br' . str_pad(Brand::max('id') + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
