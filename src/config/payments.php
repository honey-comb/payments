<?php

return [
    'paysera' => [
        'project_id' => env('PAYSERA_PROJECT_ID', ''),
        'sign_password' => env('PAYSERA_SIGN_PASSWORD', ''),

        'lang' => env('PAYSERA_LANGUAGE_CODE', 'lit'),
        'currency' => env('PAYSERA_CURRENCY', 'EUR'),
        'country_code' => env('PAYSERA_COUNTRY_CODE', 'lt'),

        'cancel_url' => env('PAYSERA_CANCEL_URL'),
        'accept_url' => env('PAYSERA_ACCEPT_URL'),
        'callback_url' => env('PAYSERA_CALLBACK_URL'),

        'payment_groups' => [
            'e-banking',
            'e-money',
            'other',
        ],

        'test' => ENV('PAYSERA_TEST', 1),
    ],
];