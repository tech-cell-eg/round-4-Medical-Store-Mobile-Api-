<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * العلاقة مع المنتجات (علاقة العديد للعديد)
     * Many-to-Many relationship with Product
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
