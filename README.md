# Recipe Sharing Platform API

A RESTful API built with **Laravel 11** and **Laravel Sanctum** (token-based auth) for the [Recipe Sharing Platform](https://github.com/istrate-mihai/recipe-sharing-platform) Vue 3 frontend.

## Stack

- **Framework:** Laravel 11
- **Auth:** Laravel Sanctum (Bearer token)
- **Database:** MySQL 8+
- **File Storage:** Cloudflare R2 (S3-compatible)
- **Payments:** Stripe (subscriptions, webhooks, billing portal)
- **PDF Generation:** barryvdh/laravel-dompdf

---

## Requirements

- PHP 8.2+
- Composer
- MySQL 8+

---

## Setup

```bash
# 1. Clone the repository
git clone https://github.com/istrate-mihai/recipe-sharing-platform-api.git
cd recipe-sharing-platform-api

# 2. Install dependencies
composer install

# 3. Copy and configure environment
cp .env.example .env
```

Edit `.env` and set your credentials:

```env
DB_DATABASE=recipe_sharing
DB_USERNAME=root
DB_PASSWORD=your_password

# Your Vue dev server origin (for CORS)
FRONTEND_URL=http://localhost:5173

# Stripe
STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx
```

```bash
# 4. Generate application key
php artisan key:generate

# 5. Run migrations and seed the database
php artisan migrate --seed

# 6. Create the public storage symlink (for image serving)
php artisan storage:link

# 7. Start the development server
php artisan serve
```

The API will be available at `http://localhost:8000`.

### Seed credentials

| Email | Password |
|-------|----------|
| ada@example.com | password |
| grace@example.com | password |

---

## Authentication

This API uses **Bearer token authentication** via Sanctum.

1. Obtain a token by calling `POST /api/auth/register` or `POST /api/auth/login`
2. Pass the token on every protected request:

```
Authorization: Bearer <your-token>
```

---

## API Reference

### Auth

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| POST | `/api/auth/register` | | Create a new account |
| POST | `/api/auth/login` | | Login and receive a token |
| POST | `/api/auth/logout` | ✓ | Revoke the current token |
| GET | `/api/auth/me` | ✓ | Get the authenticated user |

#### POST `/api/auth/register`

```json
{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123",
    "bio": "I love cooking."
}
```

Response `201`:

```json
{
    "user": {
        "id": 3,
        "name": "Jane Doe",
        "email": "jane@example.com",
        "avatar": "JA",
        "bio": "I love cooking.",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "plan": "free",
        "subscription_status": null,
        "remaining_free_recipes": 10,
        "recipe_count": 0
    },
    "token": "3|plainTextToken..."
}
```

---

### Recipes

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| GET | `/api/recipes` | | List all recipes (paginated) |
| GET | `/api/recipes/{id}` | | Get a single recipe |
| POST | `/api/recipes` | ✓ | Create a recipe |
| POST | `/api/recipes/{id}` | ✓ owner | Update a recipe |
| DELETE | `/api/recipes/{id}` | ✓ owner | Delete a recipe |
| GET | `/api/recipes/{id}/export-pdf` | ✓ premium | Download recipe card as PDF |

#### GET `/api/recipes` — Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `search` | string | Search by title or description |
| `category` | string | Filter by category |
| `difficulty` | string | Filter by difficulty |
| `per_page` | integer | Results per page (default: 15) |

---

### Subscriptions

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| GET | `/api/subscription` | ✓ | Get current plan status |
| POST | `/api/subscribe` | ✓ | Create a Stripe Checkout session |
| POST | `/api/billing-portal` | ✓ | Open Stripe Billing Portal |
| POST | `/api/webhook/stripe` | public | Stripe webhook receiver |

#### GET `/api/subscription`

```json
{
    "plan": "premium",
    "status": "active",
    "ends_at": null,
    "price_id": "price_xxx",
    "remaining_free_recipes": null
}
```

#### POST `/api/subscribe`

```json
{ "price_id": "price_xxx" }
```

Response:

```json
{ "checkout_url": "https://checkout.stripe.com/pay/cs_test_..." }
```

#### POST `/api/billing-portal`

Response:

```json
{ "portal_url": "https://billing.stripe.com/session/..." }
```

---

### Likes

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| POST | `/api/recipes/{id}/like` | ✓ | Toggle like on/off |

---

### Favourites

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| POST | `/api/recipes/{id}/favourite` | ✓ | Toggle favourite on/off |
| GET | `/api/favourites` | ✓ | List the authenticated user's saved recipes |

---

### Profile

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| GET | `/api/profile` | ✓ | Own profile and recipes |
| POST | `/api/profile` | ✓ | Update name, email, bio, password, avatar |
| GET | `/api/users/{id}` | | Public profile of any user |

---

## Subscription Tiers

### Free
- Up to **10 published recipes**
- 1 image per recipe
- Public profile
- Likes and favourites
- Community browsing
- AdSense-supported

### Premium (€4.99/month or €39/year)
- **Unlimited recipes**
- Up to 5 images per recipe
- Ad-free experience
- Private / draft recipes
- **Printable recipe cards (PDF export)**
- Nutritional info + serving calculator *(coming soon)*
- Priority support

---

## Stripe Webhook Events

The following events are handled by `WebhookController`:

| Event | Action |
|-------|--------|
| `customer.subscription.created` | Creates subscription record, activates Premium |
| `customer.subscription.updated` | Updates subscription status |
| `customer.subscription.deleted` | Marks subscription canceled |
| `invoice.payment_succeeded` | Ensures status stays `active` after renewal |
| `invoice.payment_failed` | Marks subscription `past_due` |

### Local webhook testing

```bash
# Install Stripe CLI, then:
stripe login
stripe listen --forward-to http://localhost:8000/api/webhook/stripe
```

Copy the printed `whsec_xxx` into your `.env` as `STRIPE_WEBHOOK_SECRET`.

---

## Database Schema

```
users
  id, name, email, password, avatar, bio, stripe_customer_id,
  remember_token, timestamps

subscriptions
  id, user_id (FK), stripe_id, stripe_customer_id, stripe_price_id,
  status, ends_at, timestamps

recipes
  id, user_id (FK), title, description, category, difficulty,
  prep_time, cook_time, ingredients (JSON), steps (JSON),
  image, likes_count, timestamps

likes
  id, user_id (FK), recipe_id (FK), created_at

favourites
  id, user_id (FK), recipe_id (FK), created_at

personal_access_tokens  (Sanctum)
```

---

## Middleware

| Alias | Class | Description |
|-------|-------|-------------|
| `premium` | `CheckPremium` | Returns 403 if user has no active subscription |

Usage:

```php
Route::middleware(['auth:sanctum', 'premium'])->group(function () {
    Route::get('recipes/{recipe}/export-pdf', ...);
});
```

---

## Environment Variables

### Laravel (Railway)

```env
APP_KEY=
APP_URL=https://api.recipe-sharing-platform.com

DB_CONNECTION=mysql
DB_HOST=
DB_PORT=3306
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

FRONTEND_URL=https://recipe-sharing-platform.com

SANCTUM_STATEFUL_DOMAINS=recipe-sharing-platform.com

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=auto
AWS_BUCKET=recipe-sharing-platform-api
AWS_ENDPOINT=https://<account_id>.r2.cloudflarestorage.com
AWS_PUBLIC_URL=https://pub-xxxx.r2.dev

STRIPE_KEY=pk_test_xxx
STRIPE_SECRET=sk_test_xxx
STRIPE_WEBHOOK_SECRET=whsec_xxx

SESSION_DRIVER=array
```

---

## Local Development

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## License

MIT
