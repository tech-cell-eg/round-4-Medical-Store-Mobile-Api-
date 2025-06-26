<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'phone' => $this->phone ?? '',
            'address1' => $this->address1,
            'address2' => $this->address2 ?? '',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
        return parent::toArray($request);
    }
}
