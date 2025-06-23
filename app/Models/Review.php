<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Review extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'reviewer_name',
        'review_date'
    ];

    protected $casts = [
        'rating' => 'integer',
        'review_date' => 'date',
    ];

    /**
     * الحصول على المنتج المرتبط بهذا التقييم
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * الحصول على المستخدم الذي قام بالتقييم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * الحصول على متوسط التقييم لمنتج معين
     */
    public static function getAverageRating($productId)
    {
        return static::where('product_id', $productId)->avg('rating');
    }

    /**
     * الحصول على عدد التقييمات لمنتج معين
     */
    public static function getReviewsCount($productId)
    {
        return static::where('product_id', $productId)->count();
    }
}
