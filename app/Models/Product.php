<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\User;

class Product extends Model
{
    use SoftDeletes;
    
    /**
     * الحقول التي يمكن تعبئتها
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'barcode',
        'image',
        'is_active',
        'category_id',
        'brand_id',
        'unit_id',
        'created_by',
        'updated_by'
    ];
    
    /**
     * الحقول التي يجب تحويلها إلى أنواع محددة
     *
     * @var array
     */
    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
    ];
    
    /**
     * العلاقة مع التصنيف
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * العلاقة مع الماركة
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }
    
    /**
     * العلاقة مع وحدة القياس
     */
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    
    /**
     * المستخدم الذي أنشأ المنتج
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * آخر مستخدم قام بتحديث المنتج
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
