<?php

return [
    'status' => [
        'pending' => 'Pending',
        'completed' => 'Completed',
        'canceled' => 'Canceled',
    ],

    'drivers' => [
        'paysera' => 'Paysera',
        'paypal' => 'Paypal',
        'opay' => 'OPAY',
    ],

    'message' => [
        'order_already_exist' => 'Order already exist!',
        'payment_canceled' => 'Payment canceled',
        'payment_accepted' => 'Payment accepted',
        'bad_amount' => 'Bad amount! :amount',
        'testing_enabled' => 'Testing enabled!',
        'driver_not_implemented' => 'Driver ":driver" not implemented.',
        'opay_payment_error' => 'OPAY Payment error',
    ],
];
