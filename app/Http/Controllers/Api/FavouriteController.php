<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    /**
     * GET /api/favourites
     * Returns all recipes the authenticated user has saved.
     */
    public function index(Request $request): JsonResponse
    {
        $recipes = $request->user()
            ->favourites()
            ->with(['user', 'likedBy', 'favouritedBy'])
            ->latest('favourites.created_at')
            ->get();

        return response()->json(RecipeResource::collection($recipes));
    }

    /**
     * POST /api/recipes/{recipe}/favourite
     * Toggles a favourite for the authenticated user.
     */
    public function toggle(Request $request, Recipe $recipe): JsonResponse
    {
        $user = $request->user();

        if ($recipe->favouritedBy()->where('user_id', $user->id)->exists()) {
            $recipe->favouritedBy()->detach($user->id);
            $favourited = false;
        } else {
            $recipe->favouritedBy()->attach($user->id);
            $favourited = true;
        }

        return response()->json(['favourited' => $favourited]);
    }
}
