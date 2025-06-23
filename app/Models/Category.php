<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'description',
        'image_url',
    ];

    /**
     * الحصول على المنتجات المرتبطة بهذا التصنيف
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * الحصول على عدد المنتجات المرتبطة بهذا التصنيف
     *
     * @return int
     */
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }
}
