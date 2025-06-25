<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Stock;

class Package extends Model
{
    // 
    protected $table = 'packages';

    protected $fillable = [
        'name',
        'quantity',
        'product_id',
        'size',
        'price',
        'sku',
        'barcode',
        'created_by',
        'updated_by',
    ];

    /**
     * الحصول على سجل المخزون الخاص بهذه العبوة
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    protected $casts = [
        'product_id' => 'integer',
        'barcode' => 'string',
        'price' => 'decimal:2',
    ];

    public $timestamps = true;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
