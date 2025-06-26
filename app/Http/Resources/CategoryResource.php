<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'slug' => $this->slug,
            'description' => $this->description,
            'image_url' => $this->when($this->image_url, function() {
                // استخدام نفس طريقة الحصول على الرابط الكامل للصورة كما في المنتجات
                if (strpos($this->image_url, 'http') === 0) {
                    return $this->image_url;
                }
                return url(\Illuminate\Support\Facades\Storage::url($this->image_url));
            }),
            'is_active' => (bool) $this->is_active,
            'parent_id' => $this->parent_id,
            'parent' => $this->when($this->parent_id && $this->relationLoaded('parent'), function () {
                return [
                    'id' => $this->parent->id,
                    'name' => $this->parent->name,
                    'slug' => $this->parent->slug,
                ];
            }),
            'children' => $this->when($this->relationLoaded('children'), function () {
                return CategoryResource::collection($this->children);
            }),
            'products_count' => $this->when(isset($this->products_count), function () {
                return (int) $this->products_count;
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
