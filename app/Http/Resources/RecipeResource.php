<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\RecipeImageResource;

class RecipeResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $authUser = $request->user();

        return [
            'id'                        => $this->id,
            'title'                     => $this->title,
            'description'               => $this->description,
            'category'                  => $this->category,
            'difficulty'                => $this->difficulty,
            'prep_time'                 => $this->prep_time,
            'cook_time'                 => $this->cook_time,
            'steps'                     => $this->steps,
            'likes_count'               => $this->likes_count,
            'created_at'                => $this->created_at,
            'updated_at'                => $this->updated_at,

            // Author info (always loaded)
            'author' => [
                'id'                    => $this->user->id,
                'name'                  => $this->user->name,
                'avatar'                => $this->user->avatar,
            ],

            // Auth-dependent flags (null when guest)
            'is_liked'                  => $authUser
                                            ? $this->likedBy->contains($authUser->id)
                                            : false,
            'is_favourited'             => $authUser
                                            ? $this->favouritedBy->contains($authUser->id)
                                            : false,
            'is_owner'                  => $authUser
                                            ? $authUser->id === $this->user_id
                                            : false,
            'status'                    => $this->status,
            'servings'                  => $this->servings,
            'ingredients'               => IngredientResource::collection($this->whenLoaded('ingredients')),
            'nutritional_info'          => $this->nutritional_info,

            'images'                    => RecipeImageResource::collection($this->whenLoaded('images')),
        ];
    }
}
