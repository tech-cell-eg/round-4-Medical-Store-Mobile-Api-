<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
    ];
    
    /**
     * الحصول على المسار الكامل للشعار
     *
     * @return string|null
     */
    public function getLogoUrlAttribute()
    {
        return $this->logo ? url('storage/' . $this->logo) : null;
    }

    /**
     * الحصول على المنتجات المرتبطة بهذه العلامة التجارية
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * الحصول على عدد المنتجات المرتبطة بهذه العلامة التجارية
     *
     * @return int
     */
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }
}
