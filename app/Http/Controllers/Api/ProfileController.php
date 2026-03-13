<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\RecipeResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * GET /api/profile
     * Returns the authenticated user's profile + their recipes.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $recipes = $user->recipes()
            ->with(['user', 'likedBy', 'favouritedBy'])
            ->latest()
            ->get();

        return response()->json([
            'user'    => new UserResource($user),
            'recipes' => RecipeResource::collection($recipes),
        ]);
    }

    /**
     * GET /api/users/{user}
     * Public: view another user's public profile + their recipes.
     */
    public function showPublic(Request $request, User $user): JsonResponse
    {
        $recipes = $user->recipes()
            ->with(['user', 'likedBy', 'favouritedBy'])
            ->latest()
            ->get();

        return response()->json([
            'user'    => new UserResource($user),
            'recipes' => RecipeResource::collection($recipes),
        ]);
    }

    /**
     * PATCH /api/profile
     * Update name, email, bio, password, avatar image.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Handle password separately — only update if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle avatar image upload
        if ($request->hasFile('avatar')) {
            // Delete old uploaded avatar (but not initials string)
            if ($user->avatar && str_contains($user->avatar, '/')) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return response()->json(new UserResource($user));
    }
}
