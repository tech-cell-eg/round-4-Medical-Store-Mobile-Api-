<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'is_active',
        'created_by',
        'updated_by',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    
    protected $appends = ['logo_url', 'logo_url_full', 'products_count'];
    
    /**
     * الحصول على المسار الكامل للشعار
     *
     * @return string|null
     */
    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }
        
        return 'storage/' . $this->logo;
    }
    
    /**
     * الحصول على المسار الكامل للشعار مع المنفذ
     *
     * @return string|null
     */
    public function getLogoUrlFullAttribute()
    {
        if (!$this->logo) {
            return null;
        }
        
        if (strpos($this->logo, 'http') === 0) {
            return $this->logo;
        }
        
        return url(Storage::url($this->logo));
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
