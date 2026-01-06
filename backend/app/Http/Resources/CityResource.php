<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
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
            'name' => $this->name, // Uses accessor based on locale
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'slug' => $this->slug,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'districts_count' => $this->whenCounted('districts'),
            'properties_count' => $this->whenCounted('properties'),
            'districts' => DistrictResource::collection($this->whenLoaded('districts')),
        ];
    }
}
