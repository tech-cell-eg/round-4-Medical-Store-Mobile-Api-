<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ingredient;
use App\Models\Review;

class Product extends Model
{
    //

    protected $table = 'products';
    protected $fillable = [
        'name',
        'description',
        'new_price',
        'old_price',
        'production_date',
        'expiry_date',
        'category_id',
        'unit_id',
        'image_url',
        'is_active',
    ];
    
    protected $appends = ['average_rating', 'reviews_count', 'image_url_full'];
    public $timestamps = true;

    /**
     * العلاقة مع وحدة القياس (علاقة متعدد - واحد)
     * Many-to-One relationship with Unit
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }



    /**
     * العلاقة مع العلامات التجارية (علاقة متعدد - واحد)
     * One-to-Many relationship with Brand
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * العلاقة مع التصنيفات (علاقة متعدد - متعدد)
     * Many-to-Many relationship with Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }


    // تم إزالة العلاقة مع العبوات بناءً على طلب مالك المنتج

    /**
     * العلاقة مع العبوات (علاقة واحد - متعدد)
     * One-to-Many relationship with Package
     */
    // public function packages()
    // {
    //     return $this->hasMany(Package::class);
    // }


    /**
     * العلاقة مع المكونات (علاقة متعدد - متعدد)
     * Many-to-Many relationship with Ingredient
     */
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class);
    }

    /**
     * العلاقة مع التقييمات (علاقة متعدد - متعدد)
     * Many-to-Many relationship with Review
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * الحصول على متوسط تقييم المنتج 
     * عن طريق حساب المتوسطات من كل التقييمات حيب القيمة النسبية لكل تقييم
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?: 0;
    }

    /**
     * الحصول على عدد المرات التى قام المستخدمين فيها باضافة تقييمات للمنتج 
     */
    public function getReviewsCountAttribute()
    {
        return $this->reviews()->count();
    }
    
    /**
     * الحصول على رابط الصورة الكامل مع المنفذ
     * 
     * @return string|null
     */
    public function getImageUrlFullAttribute()
    {
        if (!$this->image_url) {
            return null;
        }
        
        // إذا كان الرابط يبدأ بـ http أو https، نعيده كما هو
        if (strpos($this->image_url, 'http') === 0) {
            return $this->image_url;
        }
        
        // وإلا نفترض أنه مسار ملف في التخزين
        return url(\Illuminate\Support\Facades\Storage::url($this->image_url));
    }
}
