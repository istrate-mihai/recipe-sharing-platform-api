<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
    /**
     * Only the recipe's author can update or delete it.
     */
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }
}
