<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamMemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        
        if ($this->image) {
            // If it's already a full URL (external), keep it
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                $data['image'] = $this->image;
            } else {
                // Otherwise, it's a relative path in storage
                $data['image'] = asset('storage/' . $this->image);
            }
        }

        return $data;
    }
}
