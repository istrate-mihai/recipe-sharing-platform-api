<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Controllers\Api\LikeController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RecipeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All routes here are prefixed with /api automatically by Laravel 11.
|
*/
// ── Authentication (public) ───────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// ── Public recipe endpoints ───────────────────────────────────────────────
Route::get('recipes', [RecipeController::class, 'index']);
Route::get('recipes/{recipe}', [RecipeController::class, 'show']);

// ── Public user profile ───────────────────────────────────────────────────
Route::get('users/{user}', [ProfileController::class, 'showPublic']);

// ── Authenticated endpoints ───────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Recipes CRUD (create / update / delete)
    Route::post('recipes', [RecipeController::class, 'store']);
    Route::post('recipes/{recipe}', [RecipeController::class, 'update']); // POST with _method=PUT for multipart
    Route::delete('recipes/{recipe}', [RecipeController::class, 'destroy']);

    // Likes
    Route::post('recipes/{recipe}/like', [LikeController::class,      'toggle']);

    // Favourites
    Route::post('recipes/{recipe}/favourite', [FavouriteController::class, 'toggle']);
    Route::get('favourites', [FavouriteController::class, 'index']);

    // Profile
    Route::get('profile', [ProfileController::class, 'show']);
    Route::post('profile', [ProfileController::class, 'update']); // POST for multipart avatar upload

    // Get current plan status (called on app boot + after checkout success)
    Route::get('/subscription', [SubscriptionController::class, 'status']);

    // Start a Stripe Checkout session → returns { checkout_url }
    Route::post('/subscribe', [SubscriptionController::class, 'checkout']);

    // Open Stripe Billing Portal → returns { portal_url }
    Route::post('/billing-portal', [SubscriptionController::class, 'billingPortal']);

    // Premium only — PDF recipe card export
    Route::middleware('premium')->group(function () {
        Route::get('recipes/{recipe}/export-pdf', [RecipeController::class, 'exportPdf']);
    });
});

Route::get('sitemap.xml', [RecipeController::class, 'sitemap']);

// ── Public: Stripe webhook (NO auth — Stripe calls this directly) ─────────────
Route::post('/webhook/stripe', [WebhookController::class, 'handle']);
