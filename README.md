# Recipe Sharing Platform API

A RESTful API built with **Laravel 11** and **Laravel Sanctum** (token-based auth) for the [Recipe Sharing Platform](https://github.com/istrate-mihai/recipe-sharing-platform) Vue 3 frontend.

## Stack

- **Framework:** Laravel 11
- **Auth:** Laravel Sanctum (Bearer token)
- **Database:** MySQL 8+
- **File Storage:** Laravel `public` disk

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

Edit `.env` and set your database credentials:

```env
DB_DATABASE=recipe_sharing
DB_USERNAME=root
DB_PASSWORD=your_password

# Your Vue dev server origin (for CORS)
FRONTEND_URL=http://localhost:5173
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
        "created_at": "2024-01-01T00:00:00.000000Z"
    },
    "token": "3|plainTextToken..."
}
```

#### POST `/api/auth/login`

```json
{
    "email": "ada@example.com",
    "password": "password"
}
```

Response `200` — same shape as register.

---

### Recipes

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| GET | `/api/recipes` | | List all recipes (paginated) |
| GET | `/api/recipes/{id}` | | Get a single recipe |
| POST | `/api/recipes` | ✓ | Create a recipe |
| POST | `/api/recipes/{id}` | ✓ owner | Update a recipe |
| DELETE | `/api/recipes/{id}` | ✓ owner | Delete a recipe |

#### GET `/api/recipes` — Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `search` | string | Search by title or description |
| `category` | string | Filter by category |
| `difficulty` | string | Filter by difficulty |
| `per_page` | integer | Results per page (default: 15) |

**Example:** `GET /api/recipes?category=pasta&difficulty=easy&per_page=10`

Response:

```json
{
    "data": [ ...recipes ],
    "meta": {
        "current_page": 1,
        "last_page": 3,
        "per_page": 15,
        "total": 40
    }
}
```

#### Recipe object shape

```json
{
    "id": 1,
    "title": "Spaghetti Carbonara",
    "description": "A classic Roman pasta...",
    "category": "pasta",
    "difficulty": "medium",
    "prep_time": 10,
    "cook_time": 20,
    "ingredients": [
        { "amount": "400g", "name": "spaghetti" }
    ],
    "steps": [
        "Bring a large pot of salted water to a boil..."
    ],
    "image_url": "http://localhost:8000/storage/recipes/photo.jpg",
    "likes_count": 54,
    "created_at": "2024-01-01T00:00:00.000000Z",
    "updated_at": "2024-01-01T00:00:00.000000Z",
    "author": {
        "id": 1,
        "name": "Ada Lovelace",
        "avatar": "AL"
    },
    "is_liked": false,
    "is_favourited": false,
    "is_owner": false
}
```

`is_liked`, `is_favourited`, and `is_owner` reflect the authenticated user's state. They are `false` for unauthenticated requests.

#### POST `/api/recipes` — Create / Update

Send as `multipart/form-data` when including an image, otherwise `application/json`.

| Field | Type | Rules |
|-------|------|-------|
| `title` | string | required, min 3 |
| `description` | string | required, min 20 |
| `category` | string | required — `breakfast` `pasta` `soup` `salad` `meat` `dessert` `vegetarian` `other` |
| `difficulty` | string | required — `easy` `medium` `hard` |
| `prep_time` | integer | required, minutes |
| `cook_time` | integer | required, minutes |
| `steps[]` | array | required, min 1 item |
| `ingredients[].name` | string | required |
| `ingredients[].amount` | string | required |
| `image` | file | optional, jpg/png/webp, max 4MB |

> **Note:** For updates, all fields are optional (`sometimes`). Use `POST` with a `_method=PUT` field in the body when sending multipart/form-data.

---

### Likes

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| POST | `/api/recipes/{id}/like` | ✓ | Toggle like on/off |

Response:

```json
{
    "liked": true,
    "likes_count": 55
}
```

Calling the endpoint again toggles the like off: `"liked": false`.

---

### Favourites

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| POST | `/api/recipes/{id}/favourite` | ✓ | Toggle favourite on/off |
| GET | `/api/favourites` | ✓ | List the authenticated user's saved recipes |

Toggle response:

```json
{
    "favourited": true
}
```

---

### Profile

| Method | Endpoint | Auth | Description |
|--------|----------|:----:|-------------|
| GET | `/api/profile` | ✓ | Own profile and recipes |
| POST | `/api/profile` | ✓ | Update name, email, bio, password, avatar |
| GET | `/api/users/{id}` | | Public profile of any user |

#### POST `/api/profile` — Update

All fields are optional. Send as `multipart/form-data` when uploading an avatar.

| Field | Type | Rules |
|-------|------|-------|
| `name` | string | min 2 |
| `email` | string | must be unique |
| `bio` | string | max 500 |
| `password` | string | min 8, also send `password_confirmation` |
| `avatar` | file | jpg/png/webp, max 2MB |

---

## Error Responses

| Status | Meaning |
|--------|---------|
| `401` | Unauthenticated — missing or invalid token |
| `403` | Forbidden — authenticated but not the resource owner |
| `404` | Resource not found |
| `422` | Validation failed — response includes an `errors` object |

Validation error shape:

```json
{
    "message": "Validation failed.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 8 characters."]
    }
}
```

---

## Database Schema

```
users
  id, name, email, password, avatar, bio, remember_token, timestamps

recipes
  id, user_id (FK), title, description, category, difficulty,
  prep_time, cook_time, ingredients (JSON), steps (JSON),
  image, likes_count, timestamps

likes
  id, user_id (FK), recipe_id (FK), created_at
  UNIQUE (user_id, recipe_id)

favourites
  id, user_id (FK), recipe_id (FK), created_at
  UNIQUE (user_id, recipe_id)

personal_access_tokens  (Sanctum)
```

---

## Frontend Integration

In your Vue app, replace local fake-user logic with real API calls. A minimal authenticated fetch helper:

```js
const BASE = 'http://localhost:8000/api'

async function apiFetch(path, options = {}) {
    const token = localStorage.getItem('token')
    const res = await fetch(`${BASE}/${path}`, {
        ...options,
        headers: {
            'Accept': 'application/json',
            ...(token ? { Authorization: `Bearer ${token}` } : {}),
            ...(options.headers ?? {}),
        },
    })
    if (!res.ok) throw await res.json()
    return res.json()
}

// Login
const { user, token } = await apiFetch('auth/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password }),
})
localStorage.setItem('token', token)

// Fetch recipes
const { data, meta } = await apiFetch('recipes?category=pasta')

// Create a recipe with an image (multipart)
const form = new FormData()
form.append('title', 'My Recipe')
form.append('image', file)
// ...other fields
await apiFetch('recipes', { method: 'POST', body: form })
```

---

## License

MIT
