<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'parent_id',
        'image_url',
        'is_active',
    ];

    protected $appends = ['image_url'];

    public $timestamps = true;
    public $softDelete = true;

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

    /**
     * الحصول على الفئة الأب لهذه الفئة
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * الحصول على الفئات الفرعية لهذه الفئة
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * الحصول على رابط الصورة الكامل
     *
     * @return string|null
     */
    public function getImageUrlAttribute()
    {
        // إذا كان الحقل image_url موجود بالفعل، نستخدمه مباشرة
        if ($this->attributes['image_url']) {
            // إذا كان الرابط يبدأ بـ http أو https، نعيده كما هو
            if (strpos($this->attributes['image_url'], 'http') === 0) {
                return $this->attributes['image_url'];
            }
            
            // وإلا نفترض أنه مسار ملف في التخزين
            return url(Storage::url($this->attributes['image_url']));
        }
        
        return null;
    }
}
