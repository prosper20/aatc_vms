<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'staff'),
    ],

    'guards' => [
        'staff' => [
            'driver' => 'session',
            'provider' => 'staff',
        ],
        'receptionist' => [
            'driver' => 'session',
            'provider' => 'receptionists',
        ],
        'operative' => [
            'driver' => 'session',
            'provider' => 'operatives',
        ],
        'sm' => [
            'driver' => 'session',
            'provider' => 'security_managers',
        ],
    ],

    'providers' => [
        'staff' => [
            'driver' => 'eloquent',
            'model' => App\Models\Staff::class,
        ],
        'receptionists' => [
            'driver' => 'eloquent',
            'model' => App\Models\Receptionist::class,
        ],
        'operatives' => [
            'driver' => 'eloquent',
            'model' => App\Models\Operative::class,
        ],
        'security_managers' => [
            'driver' => 'eloquent',
            'model' => App\Models\SecurityManager::class,
        ],
    ],

    'passwords' => [
        'staff' => [
            'provider' => 'staff',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'receptionists' => [
            'provider' => 'receptionists',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'operatives' => [
            'provider' => 'operatives',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
        'security_managers' => [
            'provider' => 'security_managers',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
