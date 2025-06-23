<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;

class Category extends Model
{
    use SoftDeletes;
    
    /**
     * الحقول التي يمكن تعبئتها
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'parent_id',
        'created_by',
        'updated_by'
    ];
    
    /**
     * الحقول التي يجب تحويلها إلى أنواع محددة
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
            if (app('auth')->check()) {
                $category->created_by = app('auth')->id();
            }
        });
        
        static::updating(function ($category) {
            $category->slug = Str::slug($category->name);
            if (app('auth')->check()) {
                $category->updated_by = app('auth')->id();
            }
        });
    }
    
    /**
     * العلاقة مع المنتجات
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    
    /**
     * العلاقة مع الفئة الأم
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    /**
     * العلاقة مع الفئات الفرعية
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    
    /**
     * المستخدم الذي أنشأ الفئة
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * آخر مستخدم قام بتحديث الفئة
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
