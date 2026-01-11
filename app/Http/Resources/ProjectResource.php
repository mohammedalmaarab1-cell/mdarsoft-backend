<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            $data['image'] = filter_var($this->image, FILTER_VALIDATE_URL) 
                ? $this->image 
                : asset('storage/' . $this->image);
        }

        if ($this->gallery && is_array($this->gallery)) {
            $data['gallery'] = array_map(function ($path) {
                return filter_var($path, FILTER_VALIDATE_URL) 
                    ? $path 
                    : asset('storage/' . $path);
            }, $this->gallery);
        }

        return $data;
    }
}
