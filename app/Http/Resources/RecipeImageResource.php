<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecipeImageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'path'       => $this->path,
            'order'      => $this->order,
            'is_primary' => $this->is_primary,
            'url'        => $this->url,
        ];
    }
}
