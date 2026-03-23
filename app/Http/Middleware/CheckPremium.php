<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPremium
{
    /**
     * Guards routes that require an active Premium subscription.
     *
     * Usage in routes/api.php:
     *   Route::middleware(['auth:sanctum', 'premium'])->group(function () {
     *       Route::post('/recipes/export-pdf', ...);
     *       Route::post('/recipes/private', ...);
     *   });
     *
     * Register in bootstrap/app.php:
     *   ->withMiddleware(function (Middleware $middleware) {
     *       $middleware->alias(['premium' => \App\Http\Middleware\CheckPremium::class]);
     *   })
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isPremium()) {
            return response()->json([
                'error'       => 'This feature requires a Premium subscription.',
                'upgrade_url' => config('app.frontend_url') . '/pricing',
            ], 403);
        }

        return $next($request);
    }
}
