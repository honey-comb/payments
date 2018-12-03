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
    ],
];
