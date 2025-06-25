<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Permission;

class Role extends Model
{
    use SoftDeletes;
    
    /**
     * الحقول التي يمكن تعبئتها
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_default'
    ];
    
    /**
     * الحقول التي يجب إخفاؤها عن عمليات التحويل
     *
     * @var array
     */
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    
    /**
     * الحقول التي يجب تحويلها إلى أنواع محددة
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean'
    ];
    
    /**
     * العلاقة مع المستخدمين
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    /**
     * العلاقة مع الصلاحيات
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }
    
    /**
     * إضافة صلاحية للدور
     */
    public function givePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }
        
        $this->permissions()->syncWithoutDetaching([$permission->id]);
        
        return $this;
    }
    
    /**
     * إزالة صلاحية من الدور
     */
    public function revokePermissionTo($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->firstOrFail();
        }
        
        $this->permissions()->detach($permission->id);
        
        return $this;
    }
    
    /**
     * التحقق مما إذا كان للدور صلاحية معينة
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }
        
        return (bool) $this->permissions->intersect([$permission])->count();
    }
}
