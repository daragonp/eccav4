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
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
],

    'payu' => [
        'api_key' => env('PAYU_API_KEY'),
        'merchant_id' => env('PAYU_MERCHANT_ID'),
        'account_id' => env('PAY_U_ACC_ID'),
        'merchant' => env('PAY_U_MARKET'),
        'base_url' => env('PAYU_BASE_URL', 'https://sandbox.checkout.payulatam.com/ppp-web-gateway-payu/'),
    ],

    'github' => [
        'run_number' => env('GITHUB_RUN_NUMBER'),
        'sha'        => env('GITHUB_SHA'),
        'ref_name'   => env('GITHUB_REF_NAME'),
    ],

    'widestream' => [
        'api_key'  => env('WIDESTREAM_API_KEY'),
        'api_base' => env('WIDESTREAM_API_BASE', 'https://widestream.app/api/v1'),
    ],

];
