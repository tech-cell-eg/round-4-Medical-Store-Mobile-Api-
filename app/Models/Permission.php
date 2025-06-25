<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * الحقول التي يمكن تعبئتها
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];
    
    /**
     * الحقول التي يجب إخفاؤها عن عمليات التحويل
     *
     * @var array
     */
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at'
    ];
    
    /**
     * العلاقة مع الأدوار
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    
    /**
     * إنشاء صلاحية جديدة أو استرجاعها إذا كانت موجودة
     */
    public static function findOrCreate($name, $displayName = null, $description = null)
    {
        $permission = static::where('name', $name)->first();
        
        if ($permission) {
            return $permission;
        }
        
        return static::create([
            'name' => $name,
            'display_name' => $displayName ?? $name,
            'description' => $description
        ]);
    }
}
