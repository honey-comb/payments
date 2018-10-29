<?php

return [
    'paysera' => [
        'project_id' => env('PAYSERA_PROJECT_ID', ''),
        'sign_password' => env('PAYSERA_SIGNATURE', ''),

        'lang' => env('PAYSERA_LANGUAGE_CODE', 'lit'),
        'currency' => env('PAYSERA_CURRENCY', 'EUR'),
        'country_code' => env('PAYSERA_COUNTRY_CODE', 'lt'),

        'payment_groups' => [
            'e-banking',
            'e-money',
            'other',
        ],

        'test' => env('PAYSERA_TEST', 1),

        'responseClass' => \HoneyComb\Payments\Paysera\HCPayseraResponse::class,
    ],
];
