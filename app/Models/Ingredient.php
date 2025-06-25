<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredients';
    protected $fillable = [
        'name',
        'description'
    ];
    public $timestamps = true;

    /**
     * العلاقة مع المنتجات (علاقة العديد للعديد)
     * Many-to-Many relationship with Product
     */
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
