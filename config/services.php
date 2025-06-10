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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'netelip' => [
        'sms_url' => env('NETELIP_SMS_URL', 'https://apps.netelip.com/sms/api.php'),
        'token' => env('NETELIP_TOKEN', '6a531d7f09d72b21cc11be29745efa1fd8fdc4113930430149bd70f474d6a22f'),
        'sender' => env('NETELIP_SENDER', 'World Adventures'),
    ],
    'googlecalendar' => [
        'calendar_id' => env('APP_DEBUG') == true ? env('CALENDAR_ID_TEST') : env('CALENDAR_ID_PRODUCTION'),
    ],
];
