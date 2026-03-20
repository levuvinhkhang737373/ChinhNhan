<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'members'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'members',
        ],
        'admin' => [
            'driver' => 'passport',
            'provider' => 'admins', 
        ],
        'admin_session' => [
            'driver' => 'session',
            'provider' => 'admins', 
        ],
        'collaborator' => [
            'driver' => 'passport',
            'provider' => 'collaborators',
        ],
        'member' => [
            'driver' => 'passport',
            'provider' => 'members', 
        ],
        'member_session' => [
        'driver' => 'session',
        'provider' => 'members',
    ],
    ],

  'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
        'members' => [
            'driver' => 'eloquent',
            'model' => App\Models\Member::class,
        ],
        'collaborators' => [
            'driver' => 'eloquent',
            'model' => App\Models\Collaborator::class,
        ],
    ],


    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'members' => [
            'provider' => 'members',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
    

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the number of seconds before a password confirmation
    | window expires and users are asked to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
