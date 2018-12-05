<?php
/**
 * @copyright 2018 innovationbase
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

return [
    'status' => [
        'pending' => 'Laukiama',
        'completed' => 'Užbaigtas',
        'canceled' => 'Atšauktas',
    ],

    'drivers' => [
        'paysera' => 'Paysera',
        'paypal' => 'Paypal',
        'opay' => 'OPAY',
    ],

    'message' => [
        'order_already_exist' => 'Užsakymas jau egzistuoja!',
        'payment_canceled' => 'Mokėjimas atšauktas',
        'payment_accepted' => 'Mokėjimas patvirtintas',
        'bad_amount' => 'Neteisinga suma! :amount',
        'testing_enabled' => 'Testavimo aplinka įjungta!',
        'driver_not_implemented' => 'Driveris ":driver" neimplementuotas.',
        'opay_payment_error' => 'OPAY Mokėjimo klaida',
    ],
];
