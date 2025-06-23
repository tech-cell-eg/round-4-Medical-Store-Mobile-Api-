<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Unit extends Model
{
    use SoftDeletes;
    
    /**
     * الحقول التي يمكن تعبئتها
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'short_name',
        'description',
        'is_active',
        'is_default',
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
        'is_default' => 'boolean',
    ];
    
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($unit) {
            if (app('auth')->check()) {
                $unit->created_by = app('auth')->id();
            }
            
            // إذا كانت هذه الوحدة هي الافتراضية، قم بإلغاء تحديد الوحدات الافتراضية الأخرى
            if ($unit->is_default) {
                static::where('is_default', true)->update(['is_default' => false]);
            }
        });
        
        static::updating(function ($unit) {
            if (app('auth')->check()) {
                $unit->updated_by = app('auth')->id();
            }
            
            // إذا كانت هذه الوحدة هي الافتراضية، قم بإلغاء تحديد الوحدات الافتراضية الأخرى
            if ($unit->is_default) {
                static::where('id', '!=', $unit->id)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
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
     * المستخدم الذي أنشأ وحدة القياس
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * آخر مستخدم قام بتحديث وحدة القياس
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
