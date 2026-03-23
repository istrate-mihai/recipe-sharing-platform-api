<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'name'                    => $this->name,
            'email'                   => $this->email,
            'avatar'                  => $this->avatar,
            'bio'                     => $this->bio,
            'created_at'              => $this->created_at,

            // Subscription fields
            'plan'                    => $this->plan(),
            'subscription_status'     => $this->subscription?->status,
            'remaining_free_recipes'  => $this->remainingFreeRecipes(),
            'recipe_count'            => $this->recipes()->count(),
        ];
    }
}
