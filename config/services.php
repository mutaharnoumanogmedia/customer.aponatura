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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'shopify' => [
        'api_url' => env('SHOPIFY_API_URL'),
        'api_token' => env('SHOPIFY_API_TOKEN'),
    ],

    'manychat' => [
        'token'      => env('MANYCHAT_TOKEN'),
        'base'       => env('MANYCHAT_BASE', 'https://api.manychat.com'),
        'flow_std'   => env('MANYCHAT_FLOW_NS_STANDARD'),
        'flow_wa'    => env('MANYCHAT_FLOW_NS_WA_TEMPLATE'),
        'rps'        => (int) env('MANYCHAT_RPS', 8),
    ],

];
