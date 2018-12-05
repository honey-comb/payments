<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment currency
    |--------------------------------------------------------------------------
    */
    'currency' => env('PAYMENT_CURRENCY', 'EUR'),

    /*
    |--------------------------------------------------------------------------
    | Payment country
    |--------------------------------------------------------------------------
    */
    'country' => env('PAYMENT_COUNTRY_CODE', 'lt'),

    /*
    |--------------------------------------------------------------------------
    | Register additional drivers or override existing
    |--------------------------------------------------------------------------
    |
    | 'paysera' => \HoneyComb\Payments\Managers\HCPayseraManager::class,
    |
    */
    'additional_drivers' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Drivers config
    |--------------------------------------------------------------------------
    |
    | Set indidvidual config for each driver
    |
    */
    'drivers' => [
        'paysera' => [
            'project_id' => env('PAYSERA_PROJECT_ID'),
            'sign_password' => env('PAYSERA_SIGNATURE'),
            'test' => env('PAYSERA_TEST', 1),
            'language' => env('PAYSERA_LANGUAGE', 'LIT'),

            'responseClass' => \HoneyComb\Payments\Responses\HCPayseraResponse::class,
        ],

        'opay' => [
            'website_id' => env('OPAY_WEBSITE_ID'),
            'user_id' => env('OPAY_USER_ID'),
            'signature' => env('OPAY_SIGNATURE'),
            'redirect_on_success' => env('OPAY_REDIRECT_ON_SUCCESS', 1),

            'test' => env('OPAY_TEST', 1),
            'standard' => env('OPAY_STANDARD', 'opay_8.1'),
            'language' => env('OPAY_LANGUAGE', 'LIT'),
            'time_limit' => env('OPAY_TIME_LIMIT', 10),

            'responseClass' => \HoneyComb\Payments\Responses\HCPayseraResponse::class,
        ],
    ],
];
