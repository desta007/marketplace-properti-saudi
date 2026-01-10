<?php

namespace App\Http\Resources;

use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'property' => [
                'id' => $this->property_id,
                'title' => $this->property?->title,
                'type' => $this->property?->type,
                'primary_image' => $this->property?->primaryImage->first()?->image_url,
            ],
            'seeker' => [
                'id' => $this->seeker_id,
                'name' => $this->seeker_name,
                'phone' => $this->seeker_phone,
                'email' => $this->seeker_email,
            ],
            'message' => $this->message,
            'source' => $this->source,
            'source_label' => Lead::sourceLabels()[$this->source] ?? $this->source,
            'status' => $this->status,
            'status_label' => Lead::statusLabels()[$this->status] ?? $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
