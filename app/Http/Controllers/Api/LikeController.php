<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * POST /api/recipes/{recipe}/like
     * Toggles a like for the authenticated user.
     */
    public function toggle(Request $request, Recipe $recipe): JsonResponse
    {
        $user = $request->user();

        if ($recipe->likedBy()->where('user_id', $user->id)->exists()) {
            // Already liked — unlike
            $recipe->likedBy()->detach($user->id);
            $recipe->decrement('likes_count');
            $liked = false;
        } else {
            // Not liked yet — like
            $recipe->likedBy()->attach($user->id);
            $recipe->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked'       => $liked,
            'likes_count' => $recipe->fresh()->likes_count,
        ]);
    }
}
