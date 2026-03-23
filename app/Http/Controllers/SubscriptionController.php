<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Checkout\Session;
use Stripe\BillingPortal\Session as PortalSession;

class SubscriptionController extends Controller
{
    /**
     * POST /api/subscribe
     *
     * Creates a Stripe Checkout session and returns the hosted URL.
     * The Vue frontend then does: window.location.href = data.checkout_url
     *
     * Body: { "price_id": "price_xxx" }
     */
    public function checkout(Request $request): JsonResponse
    {
        $request->validate([
            'price_id' => ['required', 'string', 'starts_with:price_'],
        ]);

        $user = $request->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        // ── Ensure we have a Stripe customer for this user ────────────────────
        if (!$user->hasStripeCustomer()) {
            $customer = Customer::create([
                'email'    => $user->email,
                'name'     => $user->name,
                'metadata' => ['user_id' => $user->id],
            ]);

            $user->update(['stripe_customer_id' => $customer->id]);
        }

        // ── Create the Checkout session ───────────────────────────────────────
        $session = Session::create([
            'customer'             => $user->stripe_customer_id,
            'payment_method_types' => ['card'],
            'mode'                 => 'subscription',
            'line_items'           => [[
                'price'    => $request->price_id,
                'quantity' => 1,
            ]],

            // Stripe redirects the browser here after payment
            'success_url' => config('app.frontend_url') . '/checkout/success?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => config('app.frontend_url') . '/pricing',

            // Useful for reconciliation in Stripe dashboard
            'metadata' => ['user_id' => $user->id],

            // Pre-fill email so the user doesn't have to type it again
            'customer_email' => $user->hasStripeCustomer() ? null : $user->email,

            // Allow promo codes on the checkout page
            'allow_promotion_codes' => true,
        ]);

        return response()->json(['checkout_url' => $session->url]);
    }

    /**
     * POST /api/billing-portal
     *
     * Returns a Stripe Billing Portal URL where the user can:
     *   - Update payment method
     *   - Cancel subscription
     *   - Download invoices
     *   - Upgrade/downgrade plan
     *
     * No custom UI needed — Stripe handles it all.
     */
    public function billingPortal(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user->hasStripeCustomer()) {
            return response()->json(['error' => 'No billing account found.'], 404);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = PortalSession::create([
            'customer'   => $user->stripe_customer_id,
            'return_url' => config('app.frontend_url') . '/settings/billing',
        ]);

        return response()->json(['portal_url' => $session->url]);
    }

    /**
     * GET /api/subscription
     *
     * Returns the current user's plan status — used by Vue on app boot
     * and after the checkout success redirect.
     */
    public function status(Request $request): JsonResponse
    {
        $user = $request->user();
        $sub  = $user->subscription;

        return response()->json([
            'plan'       => $user->plan(),
            'status'     => $sub?->status,
            'ends_at'    => $sub?->ends_at?->toISOString(),
            'price_id'   => $sub?->stripe_price_id,
            // Expose remaining free recipes so Vue can show the progress bar
            'remaining_free_recipes' => $user->remainingFreeRecipes(),
        ]);
    }
}
