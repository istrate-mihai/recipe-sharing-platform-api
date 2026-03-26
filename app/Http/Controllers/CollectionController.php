<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Http\Requests\AddRecipeToCollectionRequest;
use App\Http\Resources\CollectionResource;
use App\Models\Collection;
use App\Models\Recipe;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    // GET /api/collections
    public function index(Request $request)
    {
        $collections = $request->user()
            ->collections()
            ->withCount('recipes')
            ->with(['recipes' => fn($q) => $q->limit(4)]) // cover thumbnails only
            ->latest()
            ->get();

        return CollectionResource::collection($collections);
    }

    // POST /api/collections
    public function store(StoreCollectionRequest $request)
    {
        $collection = $request->user()->collections()->create($request->validated());

        return new CollectionResource($collection);
    }

    // GET /api/collections/{collection}
    public function show(Request $request, Collection $collection)
    {
        $this->authorize('view', $collection);

        $collection->load('recipes.images'); // full recipe detail with images

        return new CollectionResource($collection);
    }

    // PUT /api/collections/{collection}
    public function update(UpdateCollectionRequest $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        $collection->update($request->validated());

        return new CollectionResource($collection);
    }

    // DELETE /api/collections/{collection}
    public function destroy(Request $request, Collection $collection)
    {
        $this->authorize('delete', $collection);

        $collection->delete();

        return response()->noContent();
    }

    // POST /api/collections/{collection}/recipes
    public function addRecipe(AddRecipeToCollectionRequest $request, Collection $collection)
    {
        $this->authorize('update', $collection);

        $recipeId = $request->validated('recipe_id');
        $order    = $request->validated('order') ?? $collection->recipes()->count();

        // syncWithoutDetaching prevents duplicate errors
        $collection->recipes()->syncWithoutDetaching([
            $recipeId => ['order' => $order],
        ]);

        return response()->json(['message' => 'Recipe added to collection.']);
    }

    // DELETE /api/collections/{collection}/recipes/{recipe}
    public function removeRecipe(Request $request, Collection $collection, Recipe $recipe)
    {
        $this->authorize('update', $collection);

        $collection->recipes()->detach($recipe->id);

        return response()->noContent();
    }
}
