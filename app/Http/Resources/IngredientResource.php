<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (is_array($this->resource)) {
            \Log::error('RecipeResource received array instead of model', ['resource' => $this->resource]);
            throw new \Exception('RecipeResource received plain array: ' . json_encode($this->resource));
        }
        return [
            'id'       => $this->id,
            'quantity' => $this->quantity,
            'unit'     => $this->unit,
            'name'     => $this->name,
            'order'    => $this->order,
        ];
    }
}
