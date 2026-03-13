<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    /**
     * GET /api/recipes
     * Public. Supports ?search=, ?category=, ?difficulty=, ?per_page=
     */
    public function index(Request $request): JsonResponse
    {
        auth()->shouldUse('sanctum');

        $recipes = Recipe::with(['user', 'likedBy', 'favouritedBy'])
            ->search($request->query('search'))
            ->category($request->query('category'))
            ->difficulty($request->query('difficulty'))
            ->latest()
            ->paginate($request->query('per_page', 15));

        return response()->json([
            'data' => RecipeResource::collection($recipes->items()),
            'meta' => [
                'current_page' => $recipes->currentPage(),
                'last_page'    => $recipes->lastPage(),
                'per_page'     => $recipes->perPage(),
                'total'        => $recipes->total(),
            ],
        ]);
    }

    /**
     * POST /api/recipes
     * Auth required.
     */
    public function store(StoreRecipeRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('recipes', 's3');
        }

        $recipe = $request->user()->recipes()->create($data);
        $recipe->load(['user', 'likedBy', 'favouritedBy']);

        return response()->json(new RecipeResource($recipe), 201);
    }

    /**
     * GET /api/recipes/{recipe}
     * Public.
     */
    public function show(Request $request, Recipe $recipe): JsonResponse
    {
        auth()->shouldUse('sanctum');

        $recipe->load(['user', 'likedBy', 'favouritedBy']);

        return response()->json(new RecipeResource($recipe));
    }

    /**
     * PUT /api/recipes/{recipe}
     * Auth + owner only.
     */
    public function update(UpdateRecipeRequest $request, Recipe $recipe): JsonResponse
    {
        Gate::authorize('update', $recipe);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old image if present
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }
            $data['image'] = $request->file('image')->store('recipes', 's3');
        }

        $recipe->update($data);
        $recipe->load(['user', 'likedBy', 'favouritedBy']);

        return response()->json(new RecipeResource($recipe));
    }

    /**
     * DELETE /api/recipes/{recipe}
     * Auth + owner only.
     */
    public function destroy(Recipe $recipe): JsonResponse
    {
        Gate::authorize('delete', $recipe);

        if ($recipe->image) {
            Storage::disk('public')->delete($recipe->image);
        }

        $recipe->delete();

        return response()->json(['message' => 'Recipe deleted.']);
    }
}
