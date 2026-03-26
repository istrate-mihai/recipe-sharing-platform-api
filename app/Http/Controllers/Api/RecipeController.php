<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        $recipes = Recipe::with(
            [
                'user',
                'likedBy',
                'favouritedBy',
                'ingredients',
                'images',
            ]
        )
            ->withCount('likedBy as likes_count')
            ->when(
                fn ($q) => $q->visible()
            )
            ->search($request->query('search'))
            ->category($request->query('category'))
            ->difficulty($request->query('difficulty'))
            ->latest()
            ->paginate($request->query('per_page', 10));

        return response()->json([
            'data' => RecipeResource::collection($recipes->getCollection()),
            'meta' => [
                'current_page' => $recipes->currentPage(),
                'last_page' => $recipes->lastPage(),
                'per_page' => $recipes->perPage(),
                'total' => $recipes->total(),
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

        $recipe = $request->user()->recipes()->create($data);

        if ($request->has('images')) {
            $this->syncImages($recipe, $request->images);
        }

        if (! empty($data['ingredients'])) {
            collect($data['ingredients'])->each(function ($ing, $i) use ($recipe) {
                $recipe->ingredients()->create([
                    'quantity' => $ing['quantity'] ?? null,
                    'unit' => $ing['unit'] ?? null,
                    'name' => $ing['name'],
                    'order' => $i,
                ]);
            });
        }

        $recipe->load(['user', 'likedBy', 'favouritedBy', 'ingredients', 'images']);

        return response()->json(new RecipeResource($recipe), 201);
    }

    /**
     * GET /api/recipes/{recipe}
     * Public.
     */
    public function show(Request $request, Recipe $recipe): JsonResponse
    {
        auth()->shouldUse('sanctum');

        if ($recipe->status !== 'published') {
            if (! auth()->check() || auth()->id() !== $recipe->user_id) {
                return response()->json(['message' => 'Not found.'], 404);
            }
        }

        $recipe->loadCount('likedBy as likes_count');

        $recipe->load([
            'user',
            'likedBy',
            'favouritedBy',
            'ingredients',
            'images',
        ]);

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

        $recipe->update($data);

        if ($request->has('images')) {
            $this->syncImages($recipe, $request->images);
        }

        if (isset($data['ingredients'])) {
            $recipe->ingredients()->delete();
            collect($data['ingredients'])->each(function ($ing, $i) use ($recipe) {
                $recipe->ingredients()->create([
                    'quantity' => $ing['quantity'] ?? null,
                    'unit' => $ing['unit'] ?? null,
                    'name' => $ing['name'],
                    'order' => $i,
                ]);
            });
        }

        $recipe->load(['user', 'likedBy', 'favouritedBy', 'ingredients', 'images']);

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

    /**
     * GET /api/sitemap.xml
     * Public. Returns a fresh sitemap with all recipes.
     */
    public function sitemap(): Response
    {
        $recipes = Recipe::select('id', 'updated_at')->visible()->latest()->get();

        $urls = '';

        // Static pages
        $staticPages = [
            ['loc' => '/',        'changefreq' => 'daily',   'priority' => '1.0'],
            ['loc' => '/about',   'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => '/contact', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => '/privacy', 'changefreq' => 'monthly', 'priority' => '0.3'],
        ];

        foreach ($staticPages as $page) {
            $urls .= "  <url>\n";
            $urls .= "    <loc>https://recipe-sharing-platform.com{$page['loc']}</loc>\n";
            $urls .= "    <changefreq>{$page['changefreq']}</changefreq>\n";
            $urls .= "    <priority>{$page['priority']}</priority>\n";
            $urls .= "  </url>\n";
        }

        // Dynamic recipe pages
        foreach ($recipes as $recipe) {
            $lastmod = Carbon::parse($recipe->updated_at)->toDateString();
            $urls .= "  <url>\n";
            $urls .= "    <loc>https://recipe-sharing-platform.com/recipe/{$recipe->id}</loc>\n";
            $urls .= "    <lastmod>{$lastmod}</lastmod>\n";
            $urls .= "    <changefreq>weekly</changefreq>\n";
            $urls .= "    <priority>0.8</priority>\n";
            $urls .= "  </url>\n";
        }

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        $xml .= $urls;
        $xml .= '</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function exportPdf(Recipe $recipe): Response
    {
        $recipe->load(['user', 'ingredients', 'images']);

        $imageData = null;
        $primaryImage = $recipe->images->firstWhere('is_primary', true)
                    ?? $recipe->images->first();

        if ($primaryImage) {
            try {
                $contents = Storage::disk('s3')->get($primaryImage->path);
                $mimeType = Storage::disk('s3')->mimeType($primaryImage->path);
                $imageData = 'data:'.$mimeType.';base64,'.base64_encode($contents);
            } catch (\Exception $e) {
                \Log::error('Failed to fetch recipe image: '.$e->getMessage());
            }
        }

        $pdf = Pdf::loadView('pdf.recipe-card', [
            'recipe' => $recipe,
            'imageData' => $imageData,
        ])->setPaper('a4', 'portrait');

        return $pdf->download(\Str::slug($recipe->title).'-recipe-card.pdf');
    }

    /**
     * GET /api/my-recipes
     * Auth required. Returns all recipes owned by the authenticated user.
     */
    public function myRecipes(Request $request): JsonResponse
    {
        $recipes = Recipe::with(['user', 'likedBy', 'favouritedBy'])
            ->withCount('likedBy as likes_count')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'data' => RecipeResource::collection($recipes),
        ]);
    }

    private function syncImages(Recipe $recipe, array $images): void
    {
        $existing = $recipe->images->keyBy('id');
        $keepIds = [];

        foreach ($images as $index => $imageData) {
            if (isset($imageData['id'])) {
                // existing image — just update order/is_primary
                $img = $existing->get($imageData['id']);
                if ($img) {
                    $img->update([
                        'order' => $index,
                        'is_primary' => $index === 0,
                    ]);
                    $keepIds[] = $img->id;
                }
            } else {
                // new upload
                $path = Storage::disk('s3')->putFile(
                    'recipes',
                    $imageData['file']
                );
                $new = $recipe->images()->create([
                    'path' => $path,
                    'order' => $index,
                    'is_primary' => $index === 0,
                ]);
                $keepIds[] = $new->id;
            }
        }

        // delete removed images
        $recipe->images()
            ->whereNotIn('id', $keepIds)
            ->each(function ($img) {
                Storage::disk('s3')->delete($img->path);
                $img->delete();
            });
    }
}
