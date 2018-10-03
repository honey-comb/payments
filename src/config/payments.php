<?php

return [
    'paysera' => [
        'project_id' => env('PAYSERA_PROJECT_ID', ''),
        'sign_password' => env('PAYSERA_SIGNATURE', ''),

        'lang' => env('PAYSERA_LANGUAGE_CODE', 'lit'),
        'currency' => env('PAYSERA_CURRENCY', 'EUR'),
        'country_code' => env('PAYSERA_COUNTRY_CODE', 'lt'),

        'cancel_route' => env('PAYSERA_CANCEL_ROUTE', 'payments.paysera.cancel'),
        'accept_route' => env('PAYSERA_ACCEPT_ROUTE', 'payments.paysera.accept'),
        'callback_route' => env('PAYSERA_CALLBACK_ROUTE', 'payments.paysera.callback'),

        'payment_groups' => [
            'e-banking',
            'e-money',
            'other',
        ],

        'test' => ENV('PAYSERA_TEST', 1),
    ],

    'views' => [
        'cancel' => env('PAYSERA_CANCEL_BLADE', 'HCPayments::cancel'),
        'accept' => env('PAYSERA_ACCEPT_BLADE', 'HCPayments::accept'),
    ]
];