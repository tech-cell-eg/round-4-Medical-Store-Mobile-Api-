<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ReviewResource;

/**
 * @OA\Schema(
 *     schema="ProductResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Product Name"),
 *     @OA\Property(property="description", type="string", example="Product Description"),
 *     @OA\Property(property="price", type="number", format="float", example=99.99),
 *     @OA\Property(property="quantity", type="integer", example=10),
 *     @OA\Property(property="is_active", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="category",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Category Name")
 *     ),
 *     @OA\Property(
 *         property="brand",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Brand Name")
 *     ),
 *     @OA\Property(
 *         property="unit",
 *         type="object",
 *         @OA\Property(property="id", type="integer", example=1),
 *         @OA\Property(property="name", type="string", example="Piece"),
 *         @OA\Property(property="symbol", type="string", example="pcs")
 *     ),
 *     @OA\Property(
 *         property="reviews",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ReviewResource")
 *     ),
 *     @OA\Property(
 *         property="review_summary",
 *         type="object",
 *         @OA\Property(property="average_rating", type="number", format="float", example=4.5),
 *         @OA\Property(property="total_reviews", type="integer", example=10),
 *         @OA\Property(
 *             property="ratings",
 *             type="object",
 *             @OA\Property(property="5_star", type="integer", example=6),
 *             @OA\Property(property="4_star", type="integer", example=3),
 *             @OA\Property(property="3_star", type="integer", example=1),
 *             @OA\Property(property="2_star", type="integer", example=0),
 *             @OA\Property(property="1_star", type="integer", example=0)
 *         )
 *     )
 * )
 */

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            /*
            ** Product Details
            */
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'production_date' => $this->production_date,
            'expiry_date' => $this->expiry_date,
            'image_url' => $this->image_url ? asset($this->image_url) : null,
            'is_active' => (bool)$this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            /*
            ** Relation With Category
            */
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'image_url' => $this->category->image_url ? asset($this->category->image_url) : null,
                ];
            }),

            /*
            ** Relation With Brand
            */
            'brand' => $this->whenLoaded('brand', function () {
                return [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name,
                    'logo_url' => $this->brand->logo_url ? asset($this->brand->logo_url) : null,
                ];
            }),

            /*
            ** Relation With Unit
            */
            'unit' => $this->whenLoaded('unit', function () {
                return [
                    'id' => $this->unit->id,
                    'name' => $this->unit->name,
                    'symbol' => $this->unit->symbol,
                ];
            }),

            /*
            ** Relation With Reviews
            */
            'reviews' => $this->whenLoaded('reviews', function () {
                return ReviewResource::collection($this->reviews);
            }),
            
            /**
             * Aggregated review data
             */
            'review_summary' => $this->whenLoaded('reviews', function () {
                return [
                    'average_rating' => round($this->reviews->avg('rating'), 1),
                    'total_reviews' => $this->reviews->count(),
                    'ratings' => [
                        '5_star' => $this->reviews->where('rating', 5)->count(),
                        '4_star' => $this->reviews->where('rating', 4)->count(),
                        '3_star' => $this->reviews->where('rating', 3)->count(),
                        '2_star' => $this->reviews->where('rating', 2)->count(),
                        '1_star' => $this->reviews->where('rating', 1)->count(),
                    ]
                ];
            }),
        ];
    }
}
