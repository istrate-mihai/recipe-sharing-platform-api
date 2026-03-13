<?php

use Laravel\Sanctum\Sanctum;

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | For SPA authentication via cookies. For token-based (mobile / Postman)
    | this is ignored — the Bearer token takes over.
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,localhost:5173,127.0.0.1,127.0.0.1:3000,127.0.0.1:5173,::1',
        Sanctum::currentApplicationUrlWithPort()
    ))),

    'guard' => ['web'],

    'expiration' => null, // tokens don't expire; set minutes if desired

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies'      => Illuminate\Cookie\Middleware\EncryptCookies::class,
        'validate_csrf_token'  => Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
    ],

];
