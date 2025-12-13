<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKPEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Microservices Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for external microservices used by the application.
    | Update the .env file to change these URLs when microservices move.
    |
    */

    'microservices' => [
        'animal_reports' => [
            'base_url' => env('ANIMAL_REPORTS_API_URL', 'http://10.26.13.235:8000'),
        ],
        'inventory' => [
            'base_url' => env('INVENTORY_API_URL', 'http://10.26.5.25:8000'),
        ],
        'das' => [
            'base_url' => env('DAS_API_URL', 'http://localhost:8000'),
        ],
    ],

];
