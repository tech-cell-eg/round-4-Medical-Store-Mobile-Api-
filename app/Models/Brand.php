<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Models\User;

class Brand extends Model
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
        'logo',
        'website',
        'is_active',
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
        
        static::creating(function ($brand) {
            $brand->slug = Str::slug($brand->name);
            if (app('auth')->check()) {
                $brand->created_by = app('auth')->id();
            }
        });
        
        static::updating(function ($brand) {
            $brand->slug = Str::slug($brand->name);
            if (app('auth')->check()) {
                $brand->updated_by = app('auth')->id();
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
     * المستخدم الذي أنشأ العلامة التجارية
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * آخر مستخدم قام بتحديث العلامة التجارية
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
