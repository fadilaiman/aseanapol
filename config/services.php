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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'translator' => [
        // Translation gateway on the GPU box. Prod reaches it over WireGuard
        // (http://10.201.0.2:8100), local dev over the office LAN
        // (http://10.63.20.200:8100). Empty URL disables the pipeline.
        'url'     => env('TRANSLATOR_GATEWAY_URL'),
        'token'   => env('TRANSLATOR_GATEWAY_TOKEN'),
        'timeout' => env('TRANSLATOR_GATEWAY_TIMEOUT', 600),
        'locales' => ['ms', 'id', 'th', 'vi', 'km', 'lo', 'my', 'tl', 'zh', 'es', 'ru'],
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
