<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'quantity',
        'location',
        'last_updated_by'
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * الحصول على العبوة المرتبطة بهذا المخزون
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * الحصول على المستخدم الذي قام بآخر تحديث للمخزون
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by');
    }

    /**
     * زيادة كمية المخزون
     *
     * @param int $quantity
     * @return bool
     */
    public function increaseStock($quantity, $userId = null)
    {
        $this->quantity += $quantity;
        $this->last_updated_by = $userId ?? (auth('api')->check() ? auth('api')->id() : null);
        return $this->save();
    }

    /**
     * تقليل كمية المخزون
     *
     * @param int $quantity
     * @return bool
     * @throws \Exception
     */
    public function decreaseStock($quantity, $userId = null)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception('الكمية المطلوبة غير متوفرة في المخزون');
        }

        $this->quantity -= $quantity;
        $this->last_updated_by = $userId ?? (auth('api')->check() ? auth('api')->id() : null);
        return $this->save();
    }

    /**
     * التحقق من توفر الكمية المطلوبة في المخزون
     *
     * @param int $quantity
     * @return bool
     */
    public function hasStock($quantity)
    {
        return $this->quantity >= $quantity;
    }
}
