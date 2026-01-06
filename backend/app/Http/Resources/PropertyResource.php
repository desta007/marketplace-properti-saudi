<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
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
            'title' => $this->title, // Uses accessor based on locale
            'title_en' => $this->title_en,
            'title_ar' => $this->title_ar,
            'description' => $this->description, // Uses accessor
            'type' => $this->type,
            'purpose' => $this->purpose,
            'price' => $this->price,
            'price_formatted' => number_format($this->price, 0) . ' SAR',
            'area_sqm' => $this->area_sqm,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'features' => $this->features ?? [],
            'rega_ad_license' => $this->rega_ad_license,
            'status' => $this->status,
            'view_count' => $this->view_count,
            'city' => new CityResource($this->whenLoaded('city')),
            'district' => new DistrictResource($this->whenLoaded('district')),
            'user' => new UserResource($this->whenLoaded('user')),
            'images' => PropertyImageResource::collection($this->whenLoaded('images')),
            'primary_image' => $this->whenLoaded('images', function () {
                $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();
                return $primary ? asset('storage/' . $primary->image_path) : null;
            }),
            'is_favorited' => $this->when(auth()->check(), function () {
                return $this->favoritedBy->contains(auth()->id());
            }),
            'created_at' => $this->created_at->toISOString(),
            'updated_at' => $this->updated_at->toISOString(),
        ];
    }
}
