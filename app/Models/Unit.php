<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $table = 'units';
    protected $fillable = [
        'name',
        'symbol',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'is_active' => 'boolean'
    ];
    public $timestamps = true;

    /**
     * الحصول على جميع المنتجات التي تستخدم هذه الوحدة
     * Get all products that use this unit
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
