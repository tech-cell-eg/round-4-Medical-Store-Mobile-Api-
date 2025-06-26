<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * تحويل المورد إلى مصفوفة.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'new_price' => (float) $this->new_price,
            'old_price' => (float) $this->old_price,
            'quantity' => (int) $this->quantity,
            'barcode' => $this->barcode,
            'image_url' => $this->image_url_full, // استخدام الدالة المساعدة التي تضمن المنفذ الصحيح
            'production_date' => $this->production_date,
            'expiry_date' => $this->expiry_date,
            'is_active' => (bool) $this->is_active,
            'average_rating' => (float) $this->average_rating,
            'reviews_count' => (int) $this->reviews_count,
            'category' => $this->when($this->relationLoaded('category'), function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),
            'brand' => $this->when($this->relationLoaded('brand'), function () {
                return $this->brand ? [
                    'id' => $this->brand->id,
                    'name' => $this->brand->name,
                    'logo_url' => $this->brand->logo_url_full ?? null,
                ] : null;
            }),
            'unit' => $this->when($this->relationLoaded('unit'), function () {
                return $this->unit ? [
                    'id' => $this->unit->id,
                    'name' => $this->unit->name,
                    'abbreviation' => $this->unit->abbreviation,
                ] : null;
            }),
            'ingredients' => $this->when($this->relationLoaded('ingredients'), function () {
                return $this->ingredients->map(function ($ingredient) {
                    return [
                        'id' => $ingredient->id,
                        'name' => $ingredient->name,
                        'description' => $ingredient->description,
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
