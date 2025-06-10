<?php
return [
    'active' => env('APP_ACTIVE'),
    'debug' => env('APP_DEBUG'),
    'stripe' => [
        'base_uri' => env('STRIPE_BASE_URI'),
        'key' => env('APP_DEBUG') == true ? env('STRIPE_KEY_T') : env('STRIPE_KEY_P'),
        'secret' => env('APP_DEBUG') == true ? env('STRIPE_SECRET_T') : env('STRIPE_SECRET_P'),
        // 'class' =>App\Services\StripeServices::class
    ],
    'mails' => [
        'mailAdmin' => env('APP_DEBUG') == true ? 'alopez@beneficiosvacacionales.mx' : 'support@world-adventures.es'
    ],
    'url_s3' => env('AWS_URL', 'https://s3.amazonaws.com'),
];
