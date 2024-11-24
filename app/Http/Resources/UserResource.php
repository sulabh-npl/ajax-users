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
            'name' => $this->name ?: 'N/A',
            'email' => $this->email ?: 'N/A',
            'phone' => $this->phone ?: 'N/A',
            'profile_image' => asset('storage/' . $this->profile_image),
            'description' => $this->description ?: 'N/A',
            'role' => $this->role->name ?? 'N/A',
        ];
    }
}
