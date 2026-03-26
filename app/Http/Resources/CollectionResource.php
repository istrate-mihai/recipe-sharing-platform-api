<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'is_public'    => $this->is_public,
            'recipe_count' => $this->whenLoaded('recipes', fn() => $this->recipes->count()),
            'recipes'      => RecipeResource::collection($this->whenLoaded('recipes')),
            'created_at'   => $this->created_at->toDateString(),
        ];
    }
}
