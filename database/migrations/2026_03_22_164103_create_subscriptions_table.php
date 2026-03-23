<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Stripe identifiers
            $table->string('stripe_id')->unique();           // sub_xxx
            $table->string('stripe_customer_id');            // cus_xxx
            $table->string('stripe_price_id');               // price_xxx

            // Status mirrors Stripe: active, trialing, past_due, canceled, incomplete
            $table->string('status');

            // Graceful cancellation: subscription stays active until period end
            $table->timestamp('ends_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('stripe_customer_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
