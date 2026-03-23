<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class WebhookController extends Controller
{
    /**
     * Stripe sends ALL subscription lifecycle events here.
     * This is the single source of truth — never trust only the redirect.
     *
     * Register in routes/api.php as a public (no-auth) route:
     *   Route::post('/webhook/stripe', [WebhookController::class, 'handle']);
     *
     * Also exclude from Sanctum/CSRF in bootstrap/app.php:
     *   ->withMiddleware(function (Middleware $middleware) {
     *       $middleware->validateCsrfTokens(except: ['api/webhook/stripe']);
     *   })
     */
    public function handle(Request $request): Response
    {
        $payload   = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        // ── Verify the webhook came from Stripe ───────────────────────────────
        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {
            return response('Invalid Stripe signature', 400);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        }

        // ── Route to the right handler ────────────────────────────────────────
        $stripeObject = $event->data->object;

        match ($event->type) {
            'customer.subscription.created',
            'customer.subscription.updated'  => $this->upsertSubscription($stripeObject),
            'customer.subscription.deleted'  => $this->markCanceled($stripeObject),

            // Invoice paid: ensure status stays 'active' after renewal
            'invoice.payment_succeeded'      => $this->handlePaymentSucceeded($stripeObject),

            // Payment failed: mark as past_due so we can show the user a warning
            'invoice.payment_failed'         => $this->handlePaymentFailed($stripeObject),

            default => null, // safely ignore all other events
        };

        // Stripe expects a 200 — anything else triggers a retry
        return response('OK', 200);
    }

    // ─── Private handlers ─────────────────────────────────────────────────────

    /**
     * Called on subscription.created and subscription.updated.
     * We upsert so it's idempotent — safe to receive the same event twice.
     */
    private function upsertSubscription(object $sub): void
    {
        $user = User::where('stripe_customer_id', $sub->customer)->first();

        if (!$user) {
            // Safety net: shouldn't happen, but log and return rather than crash
            \Log::warning('Stripe webhook: no user found for customer', [
                'stripe_customer_id' => $sub->customer,
            ]);
            return;
        }

        $endsAt = null;
        if ($sub->cancel_at) {
            $endsAt = Carbon::createFromTimestamp($sub->cancel_at);
        }

        $user->subscription()->updateOrCreate(
            ['stripe_id' => $sub->id],
            [
                'stripe_customer_id' => $sub->customer,
                'stripe_price_id'    => $sub->items->data[0]->price->id,
                'status'             => $sub->status,   // active, trialing, past_due, etc.
                'ends_at'            => $endsAt,
            ]
        );
    }

    /**
     * Called on subscription.deleted — hard cancel from Stripe side.
     * We set ends_at to now so isPremium() immediately returns false.
     */
    private function markCanceled(object $sub): void
    {
        Subscription::where('stripe_id', $sub->id)->update([
            'status'  => 'canceled',
            'ends_at' => now(),
        ]);
    }

    /**
     * Successful renewal — keep status as 'active'.
     * Handles the case where a past_due subscription gets paid and recovers.
     */
    private function handlePaymentSucceeded(object $invoice): void
    {
        if (!$invoice->subscription) {
            return; // one-time payment, not a subscription invoice
        }

        Subscription::where('stripe_id', $invoice->subscription)
            ->update(['status' => 'active']);
    }

    /**
     * Payment failed — mark as past_due so the frontend can show a warning
     * banner prompting the user to update their payment method.
     */
    private function handlePaymentFailed(object $invoice): void
    {
        if (!$invoice->subscription) {
            return;
        }

        Subscription::where('stripe_id', $invoice->subscription)
            ->update(['status' => 'past_due']);
    }
}
