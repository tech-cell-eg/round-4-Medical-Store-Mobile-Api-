<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'order_total' => $this->items->sum(function ($item) {
                return ($item->product->price - ($item->product->discount ?? 0)) * $item->quantity;
            }),
            'shipping' => $this->shipping,
            'total' => $this->items->sum(function ($item) {
                return ($item->product->price - ($item->product->discount ?? 0)) * $item->quantity;
            }) + $this->shipping,
            'items_count' => $this->items->count(),
            'items' => CartItemResource::collection($this->items),
        ];
        return parent::toArray($request);
    }
}
