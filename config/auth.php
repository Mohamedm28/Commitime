<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option defines the default authentication "guard" and password
    | reset "broker" for your application. You may change these values
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'sanctum', // Changed to sanctum for API authentication
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    |
    | Next, you may define every authentication guard for your application.
    | A default configuration has been defined for you which utilizes
    | session storage and the Eloquent user provider.
    |
    */

    'guards' => [
        'web' => [
            'driver' => 'session', // Keep session for web authentication
            'provider' => 'users',
        ],

        'api' => [ // Added API guard using Sanctum
            'driver' => 'sanctum',
            'provider' => 'users',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    |
    | All authentication guards have a user provider, which defines how the
    | users are retrieved from the database or other storage system.
    |
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Removed unnecessary env() usage
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    |
    | Configuration options for Laravel's password reset functionality.
    |
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | The amount of seconds before a password confirmation window expires.
    |
    */

    'password_timeout' => 10800,

];
