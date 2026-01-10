<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'whatsapp_number' => $this->whatsapp_number,
            'whatsapp_url' => $this->whatsapp_url,
            'role' => $this->role,
            'language' => $this->language,
            'avatar' => $this->avatar ? asset('storage/' . $this->avatar) : null,
            'email_verified_at' => $this->email_verified_at?->toISOString(),
            'created_at' => $this->created_at->toISOString(),

            // Agent-specific fields (only for agents)
            'rega_license_number' => $this->when($this->role === 'agent', $this->rega_license_number),
            'agent_status' => $this->when($this->role === 'agent', $this->agent_status),
            'agent_verified_at' => $this->when($this->role === 'agent', $this->agent_verified_at?->toISOString()),
            'is_verified_agent' => $this->when($this->role === 'agent', $this->isVerifiedAgent()),
        ];
    }
}

